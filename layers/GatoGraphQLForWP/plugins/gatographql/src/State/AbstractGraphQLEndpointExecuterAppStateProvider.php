<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\State;

use GatoGraphQL\GatoGraphQL\Services\EndpointExecuters\GraphQLEndpointExecuterInterface;
use PoPAPI\API\Response\Schemes as APISchemes;
use PoPAPI\GraphQLAPI\DataStructureFormatters\GraphQLDataStructureFormatter;
use PoP\Root\App;
use PoP\Root\State\AbstractAppStateProvider;

abstract class AbstractGraphQLEndpointExecuterAppStateProvider extends AbstractAppStateProvider
{
    use ExecutingGraphQLRequestAppStateProviderTrait;

    private ?GraphQLDataStructureFormatter $graphQLDataStructureFormatter = null;

    final protected function getGraphQLDataStructureFormatter(): GraphQLDataStructureFormatter
    {
        if ($this->graphQLDataStructureFormatter === null) {
            /** @var GraphQLDataStructureFormatter */
            $graphQLDataStructureFormatter = $this->instanceManager->getInstance(GraphQLDataStructureFormatter::class);
            $this->graphQLDataStructureFormatter = $graphQLDataStructureFormatter;
        }
        return $this->graphQLDataStructureFormatter;
    }

    abstract protected function getGraphQLEndpointExecuter(): GraphQLEndpointExecuterInterface;

    public function isServiceEnabled(): bool
    {
        return $this->getGraphQLEndpointExecuter()->isServiceEnabled();
    }

    /**
     * @param array<string,mixed> $state
     */
    public function initialize(array &$state): void
    {
        $state['scheme'] = APISchemes::API;
        $state['datastructure'] = $this->getGraphQLDataStructureFormatter()->getName();

        /**
         * Artificial state, to signify that this is indeed
         * a GraphQL request.
         */
        $this->addExecutingGraphQLState($state);
    }

    /**
     * @param array<string,mixed> $state
     */
    public function consolidate(array &$state): void
    {
        /**
         * Get the query and variables from the implementing class
         */
        $graphQLQueryVariablesEntry = $this->getGraphQLEndpointExecuter()->getGraphQLQueryAndVariables(App::getState(['routing', 'queried-object']));
        if ($graphQLQueryVariablesEntry->query === null) {
            // If there is no query, nothing to do!
            return;
        }

        $state['query'] = $graphQLQueryVariablesEntry->query;

        /**
         * Merge the variables into $state?
         *
         * Normally, GraphQL variables must not override the variables from the request
         * But this behavior can be overridden for the persisted query,
         * by setting "Accept Variables as URL Params" => false
         * When editing in the editor, 'queried-object' will be null, and that's OK
         */
        $graphQLVariables = $graphQLQueryVariablesEntry->variables ?? [];
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
