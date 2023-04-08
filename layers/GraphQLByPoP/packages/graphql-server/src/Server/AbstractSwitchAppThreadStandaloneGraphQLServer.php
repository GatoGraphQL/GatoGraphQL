<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Server;

use PoP\ComponentModel\App;
use PoP\ComponentModel\AppThreadInterface;
use PoP\ComponentModel\ExtendedSpec\Execution\ExecutableDocument;
use PoP\Root\Container\ContainerCacheConfiguration;
use PoP\Root\HttpFoundation\Response;
use PoP\Root\Module\ModuleInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * This class is currently not used, but its code can
 * eventually be useful, hence it's been kept as an
 * abstract class.
 */
abstract class AbstractSwitchAppThreadStandaloneGraphQLServer extends StandaloneGraphQLServer
{
    private AppThreadInterface $appThread;

    /**
     * @param array<class-string<ModuleInterface>> $moduleClasses The component classes to initialize, including those dealing with the schema elements (posts, users, comments, etc)
     * @param array<class-string<ModuleInterface>,array<string,mixed>> $moduleClassConfiguration Predefined configuration for the components
     * @param array<class-string<CompilerPassInterface>> $systemContainerCompilerPassClasses
     * @param array<class-string<CompilerPassInterface>> $applicationContainerCompilerPassClasses
     */
    public function __construct(
        array $moduleClasses,
        array $moduleClassConfiguration = [],
        array $systemContainerCompilerPassClasses = [],
        array $applicationContainerCompilerPassClasses = [],
        ?ContainerCacheConfiguration $containerCacheConfiguration = null,
    ) {
        /**
         * Keep the current AppThread, create a new one,
         * initialize it, and then restore the current AppThread.
         */
        $currentAppThread = App::getAppThread();

        parent::__construct(
            $moduleClasses,
            $moduleClassConfiguration,
            $systemContainerCompilerPassClasses,
            $applicationContainerCompilerPassClasses,
            $containerCacheConfiguration,
        );

        // Store the own AppThread
        /** @var AppThreadInterface */
        $appThread = App::getAppThread();
        $this->appThread = $appThread;

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
}
