<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\State;

use GraphQLAPI\GraphQLAPI\Services\EndpointExecuters\EndpointExecuterInterface;
use GraphQLAPI\GraphQLAPI\Services\EndpointExecuters\GraphQLQueryResolutionEndpointExecuterInterface;
use GraphQLByPoP\GraphQLQuery\Schema\GraphQLQueryConvertorInterface;
use GraphQLByPoP\GraphQLQuery\Schema\OperationTypes;
use PoP\API\Response\Schemes as APISchemes;
use PoP\GraphQLAPI\DataStructureFormatters\GraphQLDataStructureFormatter;
use PoP\Root\App;

abstract class AbstractGraphQLQueryResolutionEndpointExecuterAppStateProvider extends AbstractEndpointExecuterAppStateProvider
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

    protected function getEndpointExecuter(): EndpointExecuterInterface
    {
        return $this->getGraphQLQueryResolutionEndpointExecuter();
    }

    abstract protected function getGraphQLQueryResolutionEndpointExecuter(): GraphQLQueryResolutionEndpointExecuterInterface;

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
        ) = $this->getGraphQLQueryResolutionEndpointExecuter()->getGraphQLQueryAndVariables(App::getState(['routing', 'queried-object']));
        if (!$graphQLQuery) {
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
        $state['variables'] = $this->getGraphQLQueryResolutionEndpointExecuter()->doURLParamsOverrideGraphQLVariables(App::getState(['routing', 'queried-object'])) ?
            array_merge(
                $graphQLVariables,
                $state['variables'] ?? []
            ) :
            array_merge(
                $state['variables'] ?? [],
                $graphQLVariables
            );

        // @todo Remove this code, to temporarily convert back from GraphQL to PoP query
        // ---------------------------------------------
        if ($state['query'] !== null) {
            list(
                $operationType,
                $fieldQuery
            ) = $this->getGraphQLQueryConvertor()->convertFromGraphQLToFieldQuery(
                $state['query'],
                $state['variables'],
                null,
            );
            $state['query'] = $fieldQuery;

            // Set the operation type and, based on it, if mutations are supported
            $state['graphql-operation-type'] = $operationType;
            $state['are-mutations-enabled'] = $operationType === OperationTypes::MUTATION;
    
            // If there was an error when parsing the query, the operationType will be null,
            // then there's no need to execute the query
            if ($operationType === null) {
                $state['does-api-query-have-errors'] = true;
            }
        }
        $state['standard-graphql'] = true;
        // ---------------------------------------------
    }
}
