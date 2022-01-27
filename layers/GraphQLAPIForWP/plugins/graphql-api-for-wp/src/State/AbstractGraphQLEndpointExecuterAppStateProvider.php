<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\State;

use GraphQLAPI\GraphQLAPI\Services\EndpointExecuters\GraphQLEndpointExecuterInterface;
use GraphQLByPoP\GraphQLQuery\Schema\GraphQLQueryConvertorInterface;
use PoPAPI\API\Response\Schemes as APISchemes;
use PoPAPI\GraphQLAPI\DataStructureFormatters\GraphQLDataStructureFormatter;
use PoP\Root\App;
use PoP\Root\State\AbstractAppStateProvider;

abstract class AbstractGraphQLEndpointExecuterAppStateProvider extends AbstractAppStateProvider
{
    private ?GraphQLDataStructureFormatter $graphQLDataStructureFormatter = null;
    private ?GraphQLQueryConvertorInterface $graphQLQueryConvertor = null;

    final public function setGraphQLDataStructureFormatter(GraphQLDataStructureFormatter $graphQLDataStructureFormatter): void
    {
        $this->graphQLDataStructureFormatter = $graphQLDataStructureFormatter;
    }
    final protected function getGraphQLDataStructureFormatter(): GraphQLDataStructureFormatter
    {
        return $this->graphQLDataStructureFormatter ??= $this->instanceManager->getInstance(GraphQLDataStructureFormatter::class);
    }
    final public function setGraphQLQueryConvertor(GraphQLQueryConvertorInterface $graphQLQueryConvertor): void
    {
        $this->graphQLQueryConvertor = $graphQLQueryConvertor;
    }
    final protected function getGraphQLQueryConvertor(): GraphQLQueryConvertorInterface
    {
        return $this->graphQLQueryConvertor ??= $this->instanceManager->getInstance(GraphQLQueryConvertorInterface::class);
    }

    abstract protected function getGraphQLEndpointExecuter(): GraphQLEndpointExecuterInterface;

    public function isServiceEnabled(): bool
    {
        return $this->getGraphQLEndpointExecuter()->isServiceEnabled();
    }

    public function initialize(array &$state): void
    {
        $state['scheme'] = APISchemes::API;
        $state['datastructure'] = $this->getGraphQLDataStructureFormatter()->getName();
    }

    public function consolidate(array &$state): void
    {
        /**
         * Get the query and variables from the implementing class
         */
        list(
            $graphQLQuery,
            $graphQLVariables
        ) = $this->getGraphQLEndpointExecuter()->getGraphQLQueryAndVariables(App::getState(['routing', 'queried-object']));
        if ($graphQLQuery === null) {
            // If there is no query, nothing to do!
            return;
        }

        $state['query'] = $graphQLQuery;

        /**
         * Merge the variables into $state?
         *
         * Normally, GraphQL variables must not override the variables from the request
         * But this behavior can be overriden for the persisted query,
         * by setting "Accept Variables as URL Params" => false
         * When editing in the editor, 'queried-object' will be null, and that's OK
         */
        $graphQLVariables ??= [];
        $state['variables'] = $this->getGraphQLEndpointExecuter()->doURLParamsOverrideGraphQLVariables(App::getState(['routing', 'queried-object'])) ?
            array_merge(
                $graphQLVariables,
                $state['variables'] ?? []
            ) :
            array_merge(
                $state['variables'] ?? [],
                $graphQLVariables
            );
    }
}
