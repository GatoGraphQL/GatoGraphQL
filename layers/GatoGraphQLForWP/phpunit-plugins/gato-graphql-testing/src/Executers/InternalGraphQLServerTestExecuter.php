<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQLTesting\Executers;

use GatoGraphQL\GatoGraphQL\App;
use GatoGraphQL\GatoGraphQL\AppHelpers;
use GatoGraphQL\GatoGraphQL\PluginAppHooks;
use GatoGraphQL\GatoGraphQL\PluginSkeleton\PluginLifecyclePriorities;
use GatoGraphQL\InternalGraphQLServer\GraphQLServer;
use PHPUnitForGatoGraphQL\GatoGraphQLTesting\Constants\Actions;
use PHPUnitForGatoGraphQL\GatoGraphQLTesting\Constants\Params;
use PoP\Root\Constants\HookNames;
use PoP\Root\HttpFoundation\Response;
use RuntimeException;
use WP_Post;
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

    public function addHooks(string $pluginAppGraphQLServerName): void
    {
        /**
         * Inject after the "consolidated" state, because
         * that's where the GraphQL query is finally retrieved.
         *
         * @see layers/GatoGraphQLForWP/plugins/gato-graphql/src/State/AbstractGraphQLEndpointExecuterAppStateProvider.php
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
         *   "external" => "internal" => "internal"
         *
         * This will be triggered when the executed query
         * contains the `createPost` mutation. When that query
         * is replicated under the "internalGraphQLServerResponses"
         * field, that will be the 3rd level of nesting.
         *
         * In addition, the "external" query will also execute
         * this hook, then another InternalGraphQLServer
         * execution will be requested there. Overall,
         * we have:
         *
         *   => "external" <= requested query
         *     => "internal" added by hook on `createPost`
         *     => "internal" added via artificial "internalGraphQLServerResponses" field
         *       => "internal" added by hook on `createPost`
         */
        \add_action(
            'wp_insert_post',
            fn (int $postID, WP_Post $post) => $this->maybeAddNestedInternalGraphQLServerQuery($pluginAppGraphQLServerName, $post),
            10,
            2
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
        // 'executing-graphql' is only set on the "external" server
        if (AppHelpers::isMainAppThread() && !$state['executing-graphql']) {
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

        if (!AppHelpers::isMainAppThread()) {
            return $state;
        }

        $query = $state['query'];
        if ($query === null) {
            return $state;
        }

        /**
         * For deep nested testing: Find out where is the last
         * query operation and insert the
         * "internalGraphQLServerResponses" field there,
         * so that it can print the results of the hook on
         * the mutation that comes before.
         */
        $matches = [];
        preg_match_all('/query .*?{/smS', $query, $matches, PREG_OFFSET_CAPTURE);
        if ($matches === []) {
            return $state;
        }
        $lastQueryMatch = $matches[0][count($matches[0]) - 1];
        $lastQueryBracketPos = $lastQueryMatch[1] + strlen($lastQueryMatch[0]);

        /**
         * Modify the query with a simple hack:
         * Append field "_appStateValue" at the beginning of
         * the last query operation
         */
        $state['query'] = substr($query, 0, $lastQueryBracketPos)
            . PHP_EOL
            . $this->getAppStateValueFieldToAppend()
            . PHP_EOL
            . substr($query, $lastQueryBracketPos);

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
        if (!AppHelpers::isMainAppThread()) {
            return;
        }

        $this->executeQueryAgainstInternalGraphQLServer();
    }

    protected function canExecuteQueryAgainstInternalGraphQLServer(
        bool $withDeepNested,
    ): bool {
        // 'executing-graphql' is only set on the "external" server
        if (AppHelpers::isMainAppThread() && !App::getState('executing-graphql')) {
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

        $response = GraphQLServer::executeQuery(
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
    public function maybeAddNestedInternalGraphQLServerQuery(
        string $pluginAppGraphQLServerName,
        WP_Post $post,
    ): void {
        if (App::getAppThread()->getName() !== $pluginAppGraphQLServerName) {
            return;
        }

        // Avoid it being executed by the WP-CLI commands
        if (!App::getAppLoader()->isReadyState()) {
            return;
        }

        if (!$this->canExecuteQueryAgainstInternalGraphQLServer(true)) {
            return;
        }

        $this->executeDeepNestedQueryAgainstInternalGraphQLServer($post);
    }

    protected function executeDeepNestedQueryAgainstInternalGraphQLServer(WP_Post $post): void
    {
        $response = $this->executeInternalGraphQLQuery($post);

        /** @var string */
        $content = $response->getContent();
        $jsonContent = json_decode($content, false);
        $this->appendInternalGraphQLServerResponseToAppState($jsonContent);
    }

    /**
     * Execute the query by any of these methods (to also test them):
     *
     *   - `GraphQLServer::executeQuery`
     *   - `GraphQLServer::executeQueryInFile`
     *   - `GraphQLServer::executePersistedQuery`
     */
    protected function executeInternalGraphQLQuery(WP_Post $post): Response
    {
        $postTitle = $post->post_title;
        $postStatus = $post->post_status;

        $file = $this->getInternalGraphQLQueryFile();
        $variables = [
            'postTitle' => $postTitle,
            'postStatus' => $postStatus,
        ];

        /** @var string[] */
        $actions = App::getState('actions');
        if (in_array(Actions::TEST_GATO_GRAPHQL_EXECUTE_QUERY_IN_FILE_METHOD, $actions)) {
            return GraphQLServer::executeQueryInFile(
                $file,
                $variables
            );
        }

        if (in_array(Actions::TEST_GATO_GRAPHQL_EXECUTE_PERSISTED_QUERY_METHOD, $actions)) {
            /**
             * This persisted query, stored in the DB data, must contain
             * the same GraphQL query as ExecuteInternalQuery.gql
             *
             * @see entry `<wp:post_id>290</wp:post_id>` (or `<title><![CDATA[Internal GraphQL Server Test]]></title>`) in `gato-graphql-data.xml`
             */
            if (App::getRequest()->query->has(Params::PERSISTED_QUERY_ID)) {
                $persistedQueryIDOrSlug = (int)App::getRequest()->query->get(Params::PERSISTED_QUERY_ID);
            } else {
                $persistedQueryIDOrSlug = App::getRequest()->query->get(Params::PERSISTED_QUERY_SLUG);
                if ($persistedQueryIDOrSlug === null) {
                    throw new RuntimeException(
                        sprintf(
                            \__('Must provide either the persisted query ID or slug, via params "%s" or "%s'),
                            Params::PERSISTED_QUERY_ID,
                            Params::PERSISTED_QUERY_SLUG
                        )
                    );
                }
                $persistedQueryIDOrSlug = (string)$persistedQueryIDOrSlug;
            }
            return GraphQLServer::executePersistedQuery(
                $persistedQueryIDOrSlug,
                $variables
            );
        }

        /** @var string */
        $query = file_get_contents($file);
        return GraphQLServer::executeQuery($query, $variables);
    }

    /**
     * Use the same query in the .gql file to test both `executeQuery`
     * and `executeQueryInFile`
     */
    protected function getInternalGraphQLQueryFile(): string
    {
        return dirname(__DIR__, 2) . '/graphql-documents/ExecuteInternalQuery.gql';
    }
}
