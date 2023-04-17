<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPITesting\Executers;

use GraphQLAPI\GraphQLAPI\App;
use GraphQLAPI\GraphQLAPI\PluginAppGraphQLServerNames;
use GraphQLAPI\GraphQLAPI\PluginAppHooks;
use GraphQLAPI\GraphQLAPI\PluginSkeleton\PluginLifecyclePriorities;
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
        \add_action(
            PluginAppHooks::INITIALIZE_APP,
            $this->addHooks(...),
            PluginLifecyclePriorities::INITIALIZE_APP
        );
    }

    public function addHooks(): void
    {
        /**
         * Inject after the "consolidated" state, because
         * that's where the GraphQL query is finally retrieved.
         *
         * @see layers/GraphQLAPIForWP/plugins/graphql-api-for-wp/src/State/AbstractGraphQLEndpointExecuterAppStateProvider.php
         */
        App::addFilter(
            HookNames::APP_STATE_CONSOLIDATED,
            $this->maybeSetupInternalGraphQLServerTesting(...)
        );

        App::addAction(
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
     * 
     * @param array<string,mixed> $state
     * @return array<string,mixed>
     */
    protected function setupInternalGraphQLServerTesting(array $state): array
    {
        $state[$this->getAppStateKey()] = null;

        if (App::getAppThread()->getName() !== PluginAppGraphQLServerNames::STANDARD) {
            return $state;
        }

        $query = $state['query'];
        if ($query === null) {
            return $state;
        }

        $firstBracketPos = strpos($query, '{');
        if ($firstBracketPos === false) {
            return $state;
        }

        /**
         * Modify the query with a simple hack:
         * Append field "_appStateValue" at the beginning of the query
         */       
        $afterFirstBracketPos = $firstBracketPos + strlen('{');
        $state['query'] = substr($query, 0, $afterFirstBracketPos)
            . PHP_EOL
            . $this->getAppStateValueFieldToAppend()
            . PHP_EOL
            . substr($query, $afterFirstBracketPos);

        return $state;
    }

    protected function getAppStateKey(): string
    {
        return 'internal-graphql-server-response';
    }

    protected function getAppStateValueFieldToAppend(): string
    {
        return sprintf(
            ' internalGraphQLServerResponse: _appStateValue(name: "%s") ',
            $this->getAppStateKey()
        );
    }

    /**
     * Execute the requested query against the `GraphQLServer`,
     * and place the response in the AppState, under key
     * "internal-graphql-server-response"
     */
    public function maybeExecuteQueryAgainstInternalGraphQLServer(): void
    {
        if (!App::getState('executing-graphql') || App::getState('query') === null) {
            return;
        }

        /** @var string[] */
        $actions = App::getState('actions');
        if (!in_array(Actions::TEST_INTERNAL_GRAPHQL_SERVER, $actions)) {
            return;
        }

        // Do not create an infinite loop
        if (App::getAppThread()->getName() !== PluginAppGraphQLServerNames::STANDARD) {
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

        // Comment out the added field
        $appStateValueField = $this->getAppStateValueFieldToAppend();
        $query = str_replace(
            $appStateValueField,
            '#' . $appStateValueField,
            $query
        );
                    
        $graphQLServer = InternalGraphQLServerFactory::getInstance();
        $response = $graphQLServer->execute(
            $query,
            $variables,
            $operationName,
        );

        /** @var string */
        $content = $response->getContent();
        $jsonContent = json_decode($content, false);

        $appStateManager = App::getAppStateManager();
        $appStateManager->override($this->getAppStateKey(), $jsonContent);
    }
}
