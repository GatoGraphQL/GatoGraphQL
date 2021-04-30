<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EndpointResolvers;

use WP_Post;
use WP_Query;
use PoP\Routing\RouteNatures;
use PoP\API\Schema\QueryInputs;
use GraphQLByPoP\GraphQLRequest\Hooks\VarsHookSet;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use GraphQLByPoP\GraphQLRequest\Execution\QueryExecutionHelpers;
use PoP\GraphQLAPI\DataStructureFormatters\GraphQLDataStructureFormatter;
use PoP\API\Response\Schemes as APISchemes;

trait EndpointResolverTrait
{
    /**
     * Execute the GraphQL query
     */
    protected function executeGraphQLQuery(): void
    {
        /**
         * Priority 1: Execute before VarsHookSet in the API package, to set-up the variables
         * in $vars as soon as we knows if it's a singular post of this type.
         * But after setting $vars['routing-state']['queried-object-id'], to get the current
         * post ID from $vars instead of the global context
         */
        \add_action(
            'ApplicationState:addVars',
            [$this, 'addGraphQLVars'],
            1,
            1
        );
        /**
         * Assign the single endpoint.
         * Execute last, to make sure it's not overriden RouteNatures::CUSTOMPOST
         * because querying the persisted query matches `is_single()`
         */
        \add_filter(
            'WPCMSRoutingState:nature',
            [$this, 'getNature'],
            PHP_INT_MAX,
            2
        );
    }

    /**
     * Assign the single endpoint by setting it as the Home nature
     */
    public function getNature(string $nature, WP_Query $query): string
    {
        return RouteNatures::HOME;
    }

    /**
     * Provide the query to execute and its variables
     *
     * @return mixed[] Array of 2 elements: [query, variables]
     */
    abstract protected function getGraphQLQueryAndVariables(?WP_Post $graphQLQueryPost): array;

    /**
     * Indicate if the GraphQL variables must override the URL params
     */
    protected function doURLParamsOverrideGraphQLVariables(?WP_Post $customPost): bool
    {
        return false;
    }

    /**
     * Check if requesting the single post of this CPT and, in this case, set the request with the needed API params
     *
     * @param array<array> $vars_in_array
     */
    public function addGraphQLVars(array $vars_in_array): void
    {
        /**
         * Remove any query passed through the request, to avoid users executing
         * a custom query, bypassing the persisted one (for Persisted Query)
         * or the one in the body (for Custom Endpoint).
         * Also fixed a bug when using GraphiQL: the query is dynamically
         * updated on the URL under /?query=..., and this is sent to the server
         */
        unset($_REQUEST[QueryInputs::QUERY]);

        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var GraphQLDataStructureFormatter */
        $graphQLDataStructureFormatter = $instanceManager->getInstance(GraphQLDataStructureFormatter::class);

        // Indicate it is an API, of type GraphQL. Just by doing is, class
        // \GraphQLByPoP\GraphQLRequest\Hooks\VarsHookSet will process the GraphQL request
        [&$vars] = $vars_in_array;
        $vars['scheme'] = APISchemes::API;
        $vars['datastructure'] = $graphQLDataStructureFormatter->getName();

        /**
         * Get the query and variables from the implementing class
         */
        list(
            $graphQLQuery,
            $graphQLVariables
        ) = $this->getGraphQLQueryAndVariables($vars['routing-state']['queried-object']);
        if (!$graphQLQuery) {
            // If there is no query, nothing to do!
            return;
        }
        /**
         * Merge the variables into $vars
         */
        if ($graphQLVariables) {
            // Normally, GraphQL variables must not override the variables from the request
            // But this behavior can be overriden for the persisted query,
            // by setting "Accept Variables as URL Params" => false
            // When editing in the editor, 'queried-object' will be null, and that's OK
            $vars['variables'] = $this->doURLParamsOverrideGraphQLVariables($vars['routing-state']['queried-object']) ?
                array_merge(
                    $graphQLVariables,
                    $vars['variables'] ?? []
                ) :
                array_merge(
                    $vars['variables'] ?? [],
                    $graphQLVariables
                );
        }
        // Extract the operationName from the payload, it's not saved on the post!
        list(
            $unneededGraphQLQuery,
            $unneededVariables,
            $operationName
        ) = QueryExecutionHelpers::extractRequestedGraphQLQueryPayload();
        // Add the query into $vars
        $instanceManager = InstanceManagerFacade::getInstance();
        /**
         * @var VarsHookSet
         */
        $graphQLAPIRequestHookSet = $instanceManager->getInstance(VarsHookSet::class);
        $graphQLAPIRequestHookSet->addGraphQLQueryToVars($vars, $graphQLQuery, $operationName);
    }
}
