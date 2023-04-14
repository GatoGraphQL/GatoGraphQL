<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Server;

use GraphQLAPI\GraphQLAPI\AppThread;
use GraphQLByPoP\GraphQLServer\Server\AbstractGraphQLServer;
use PoP\ComponentModel\App;
use PoP\ComponentModel\AppThreadInterface;
use PoP\ComponentModel\ExtendedSpec\Execution\ExecutableDocument;
use PoP\Root\HttpFoundation\Response;
use PoP\Root\StateManagers\HookManagerInterface;
use PoP\RootWP\StateManagers\HookManager;

class InternalGraphQLServer extends AbstractGraphQLServer
{
    private AppThreadInterface $appThread;

    /**
     * Initialize the App with a new AppThread
     */
    public function __construct()
    {
        $this->appThread = new AppThread();
        
        /**
         * Keep the current AppThread, switch to the GraphQLServer's
         * one, initialize the App, and then restore the current AppThread.
         */
        $currentAppThread = App::getAppThread();
        App::setAppThread($this->appThread);

        // Initialize the App
        // ...

        // Restore the original AppThread
        App::setAppThread($currentAppThread);
    }

    /**
     * The basic state for executing GraphQL queries is already set.
     * In addition, inject the actual GraphQL query and variables,
     * build the AST, and generate and print the data.
     *
     * @param array<string,mixed> $variables
     */
    public function execute(
        string|ExecutableDocument $queryOrExecutableDocument,
        array $variables = [],
        ?string $operationName = null
    ): Response {
        /**
         * Keep the current AppThread, switch to the GraphQLServer's
         * one, resolve the query, and then restore the current AppThread.
         */
        $currentAppThread = App::getAppThread();
        App::setAppThread($this->appThread);

        $response = parent::execute(
            $queryOrExecutableDocument,
            $variables,
            $operationName,
        );

        // Restore the original AppThread
        App::setAppThread($currentAppThread);

        return $response;
    }

    protected function getHookManager(): HookManagerInterface
    {
        return new HookManager();
    }
}
