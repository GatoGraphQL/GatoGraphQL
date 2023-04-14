<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Server;

use GraphQLByPoP\GraphQLServer\Server\AbstractGraphQLServer;
use PoP\ComponentModel\App;
use PoP\ComponentModel\AppThread;
use PoP\ComponentModel\AppThreadInterface;
use PoP\ComponentModel\ExtendedSpec\Execution\ExecutableDocument;
use PoP\Root\HttpFoundation\Response;

abstract class AbstractAttachedGraphQLServer extends AbstractGraphQLServer
{
    private AppThreadInterface $appThread;

    /**
     * Initialize the App with a new AppThread
     */
    public function __construct()
    {
        $this->appThread = $this->createAppThread();
        
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

    protected function createAppThread(): AppThreadInterface
    {
        return new AppThread();
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
}
