<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\State;

use PoP\API\Response\Schemes as APISchemes;
use PoP\GraphQLAPI\DataStructureFormatters\GraphQLDataStructureFormatter;
use PoP\Root\App;

abstract class AbstractGraphQLQueryResolutionEndpointExecuterAppStateProvider extends AbstractEndpointExecuterAppStateProvider
{
    private ?GraphQLDataStructureFormatter $graphQLDataStructureFormatter = null;

    final public function setGraphQLDataStructureFormatter(GraphQLDataStructureFormatter $graphQLDataStructureFormatter): void
    {
        $this->graphQLDataStructureFormatter = $graphQLDataStructureFormatter;
    }
    final protected function getGraphQLDataStructureFormatter(): GraphQLDataStructureFormatter
    {
        return $this->graphQLDataStructureFormatter ??= $this->instanceManager->getInstance(GraphQLDataStructureFormatter::class);
    }

    public function initialize(array &$state): void
    {
        $state['scheme'] = APISchemes::API;
        $state['datastructure'] = $this->getGraphQLDataStructureFormatter()->getName();

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
         */
        if (!$graphQLVariables) {
            return;
        }

        // Normally, GraphQL variables must not override the variables from the request
        // But this behavior can be overriden for the persisted query,
        // by setting "Accept Variables as URL Params" => false
        // When editing in the editor, 'queried-object' will be null, and that's OK
        $state['variables'] = $this->getGraphQLQueryResolutionEndpointExecuter()->doURLParamsOverrideGraphQLVariables(App::getState(['routing', 'queried-object'])) ?
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
