<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPITesting\Executers;

use GraphQLAPI\GraphQLAPI\App;
use GraphQLAPI\GraphQLAPI\Server\InternalGraphQLServerFactory;
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
        /**
         * Please notice: we can't use App::addFilter as it has
         * not been initialized yet.
         *
         * As a good consecuence, the filter is only added
         * on the "standard" AppThread, and not in all of them
         * (i.e. not on "internal"). That's why key
         * "internal-graphql-server-response" will not exist
         * in the AppState when resolving the query against
         * the InternalGraphQLServer.
         */
        \add_filter(
            HookNames::APP_STATE_CONSOLIDATED,
            $this->maybeSetupInternalGraphQLServerTesting(...)
        );

        \add_action(
            HookNames::APPLICATION_READY,
            $this->maybeExecuteQueryAgainstInternalGraphQLServer(...)
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

    /**
     * Execute the requested query against the `GraphQLServer`,
     * and place the response in the AppState, under key
     * "internal-graphql-server-response"
     */
    public function maybeExecuteQueryAgainstInternalGraphQLServer(): void
    {
        $appStateKey = 'internal-graphql-server-response';
        if (!App::hasState($appStateKey)) {
            return;
        }

        $this->executeQueryAgainstInternalGraphQLServer();
    }

    /**
     * Execute the requested query against the `GraphQLServer`,
     * and place the response in the AppState, under key
     * "internal-graphql-server-response"
     */
    protected function executeQueryAgainstInternalGraphQLServer(): void
    {
        /** @var string */
        $query = App::getState('query');
        /** @var array<string,mixed> */
        $variables = App::getState('variables');
        /** @var string|null */
        $operationName = App::getState('operation-name');
        
        $graphQLServer = InternalGraphQLServerFactory::getInstance();
        $response = $graphQLServer->execute(
            $query,
            $variables,
            $operationName,
        );

        /** @var string */
        $content = $response->getContent();
        $jsonContent = json_decode($content, false);

        $appStateKey = 'internal-graphql-server-response';
        $appStateManager = App::getAppStateManager();
        $appStateManager->override($appStateKey, $jsonContent);
    }
}
