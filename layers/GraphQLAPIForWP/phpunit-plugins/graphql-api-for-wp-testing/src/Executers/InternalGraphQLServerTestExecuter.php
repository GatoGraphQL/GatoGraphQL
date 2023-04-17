<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPITesting\Executers;

use PHPUnitForGraphQLAPI\GraphQLAPITesting\Constants\Actions;
use PoP\Root\Constants\HookNames;

class InternalGraphQLServerTestExecuter
{
    /**
     * Inject the "internal-graphql-server-response" state
     * containing the response of the same requested query
     * via the endpoint, but executed against `GraphQLServer`.
     */
    public function __construct()
    {
        add_filter(
            HookNames::APP_STATE_CONSOLIDATED,
            $this->maybeSetupInternalGraphQLServerTesting(...)
        );
    }

    /**
     * When resolving a GraphQL query, check if the params
     * are set in the request to test the `InternalGraphQLServer`
     *
     * @param array<string,mixed> $state
     * @return array<string,mixed>
     */
    public function maybeSetupInternalGraphQLServerTesting(array $state): array
    {
        if (!$state['executing-graphql'] || $state['query'] === null) {
            return $state;
        }

        /** @var string[] */
        $actions = $state['actions'];
        if (!in_array(Actions::TEST_INTERNAL_GRAPHQL_SERVER, $actions)) {
            return $state;
        }

        return $this->setupInternalGraphQLServerTesting($state);
    }

    /**
     * Set-up the GraphQL queries to execute to test
     * the `InternalGraphQLServer`
     */
    protected function setupInternalGraphQLServerTesting(array $state): array
    {
        $appStateKey = 'internal-graphql-server-response';
        $state[$appStateKey] = null;

        /**
         * Modify the query with a simple hack:
         * Append field "_appStateValue" at the end of the query
         *
         * @var string
         */
        $query = $state['query'];
        $closingBracketPost = strrpos($query, '}');
        $query = substr(
            $query,
            0,
            $closingBracketPost
        ) . sprintf(
            ' internalGraphQLServerResponse: _appStateValue(name: "%s") ',
            $appStateKey
        ) . substr(
            $query,
            $closingBracketPost
        );
        $state['query'] = $query;
        return $state;
    }
}
