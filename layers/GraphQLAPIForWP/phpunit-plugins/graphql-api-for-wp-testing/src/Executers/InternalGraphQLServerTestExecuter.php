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
use stdClass;

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

        /**
         * Test 3 levels of nesting:
         *
         *   "standard" => "internal" => "internal"
         *
         * This will be triggered when the executed query
         * contains the `createPost` mutation. When that query
         * is replicated under the "internalGraphQLServerResponse"
         * field, that will be the 3rd level of nesting.
         *
         * In addition, the "standard" query will also execute
         * this hook, then another InternalGraphQLServer
         * execution will be requested there. Overall,
         * we have:
         *
         *   => "standard" <= requested query
         *     => "internal" added by hook on `createPost`
         *     => "internal" added via artificial "internalGraphQLServerResponse" field
         *       => "internal" added by hook on `createPost`
         */
        \add_action(
            'wp_insert_post',
            $this->maybeAddNestedInternalGraphQLServerQuery(...)
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
        // 'executing-graphql' is only set on the "standard" server
        if (
            App::getAppThread()->getName() === PluginAppGraphQLServerNames::STANDARD
            && !$state['executing-graphql']
        ) {
            return $state;
        }

        if ($state['query'] === null) {
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
        $state[$this->getInternalGraphQLServerResponsesAppStateKey()] = new stdClass();

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

    protected function getInternalGraphQLServerResponsesAppStateKey(): string
    {
        return 'internal-graphql-server-responses';
    }

    protected function getAppStateValueFieldToAppend(): string
    {
        return sprintf(
            ' internalGraphQLServerResponses: _appStateValue(name: "%s") ',
            $this->getInternalGraphQLServerResponsesAppStateKey()
        );
    }

    /**
     * Execute the requested query against the `GraphQLServer`,
     * and place the response in the AppState, under key
     * "internal-graphql-server-response"
     */
    public function maybeExecuteQueryAgainstInternalGraphQLServer(): void
    {
        if (!$this->canExecuteQueryAgainstInternalGraphQLServer(false)) {
            return;
        }

        // Do not create an infinite loop
        if (App::getAppThread()->getName() !== PluginAppGraphQLServerNames::STANDARD) {
            return;
        }

        $this->executeQueryAgainstInternalGraphQLServer();
    }

    protected function canExecuteQueryAgainstInternalGraphQLServer(
        bool $withDeepNested,
    ): bool {
        // 'executing-graphql' is only set on the "standard" server
        if (
            App::getAppThread()->getName() === PluginAppGraphQLServerNames::STANDARD
            && !App::getState('executing-graphql')
        ) {
            return false;
        }

        if (App::getState('query') === null) {
            return false;
        }

        /** @var string[] */
        $actions = App::getState('actions');
        if (!in_array(Actions::TEST_INTERNAL_GRAPHQL_SERVER, $actions)) {
            return false;
        }

        if (
            $withDeepNested
            && !in_array(Actions::TEST_DEEP_NESTED_INTERNAL_GRAPHQL_SERVER, $actions)
        ) {
            return false;
        }

        return true;
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

        // If not testing deep nested, then comment out the added field
        /** @var string[] */
        $actions = App::getState('actions');
        if (!in_array(Actions::TEST_DEEP_NESTED_INTERNAL_GRAPHQL_SERVER, $actions)) {
            $appStateValueField = $this->getAppStateValueFieldToAppend();
            $query = str_replace(
                $appStateValueField,
                '#' . $appStateValueField,
                $query
            );
        }

        $graphQLServer = InternalGraphQLServerFactory::getInstance();
        $response = $graphQLServer->execute(
            $query,
            $variables,
            $operationName,
        );

        /** @var string */
        $content = $response->getContent();
        $jsonContent = json_decode($content, false);
        $this->appendInternalGraphQLServerResponseToAppState($jsonContent);
    }

    /**
     * Append the execution query, and its response, under a
     * new key "exect_{#number}" in the JSON object
     */
    protected function appendInternalGraphQLServerResponseToAppState(stdClass $jsonContent): void
    {
        $internalGraphQLServerResponsesAppStateKey = $this->getInternalGraphQLServerResponsesAppStateKey();
        /** @var stdClass */
        $internalGraphQLServerResponses = App::getState($internalGraphQLServerResponsesAppStateKey);

        $existingExecutionCount = count((array)$internalGraphQLServerResponses);
        $executionKey = 'exec_' . ($existingExecutionCount + 1);
        $internalGraphQLServerResponses->$executionKey = $jsonContent;

        $appStateManager = App::getAppStateManager();
        $appStateManager->override($internalGraphQLServerResponsesAppStateKey, $internalGraphQLServerResponses);
    }

    /**
     * Test a 3rd level of nesting by executing
     * yet another query against the InternalGraphQLServer
     * by hooking on mutation `createPost`.
     */
    public function maybeAddNestedInternalGraphQLServerQuery(): void
    {
        if (!$this->canExecuteQueryAgainstInternalGraphQLServer(true)) {
            return;
        }

        // // Do not create an infinite loop
        // if (App::getAppThread()->getName() !== PluginAppGraphQLServerNames::STANDARD) {
        //     return;
        // }

        $this->executeDeepNestedQueryAgainstInternalGraphQLServer();
    }

    protected function executeDeepNestedQueryAgainstInternalGraphQLServer(): void
    {
        /** @var string */
        $query = <<<GRAPHQL
            {
                insideDeepNestedQuery: id
                executingQuery: _appStateValue(name: "query")
            }
        GRAPHQL;

        // // Comment out the added field
        // $appStateValueField = $this->getAppStateValueFieldToAppend();
        // $query = str_replace(
        //     $appStateValueField,
        //     '#' . $appStateValueField,
        //     $query
        // );

        $graphQLServer = InternalGraphQLServerFactory::getInstance();
        $response = $graphQLServer->execute(
            $query,
            // $variables,
            // $operationName,
        );

        /** @var string */
        $content = $response->getContent();
        $jsonContent = json_decode($content, false);
        $this->appendInternalGraphQLServerResponseToAppState($jsonContent);
    }
}
