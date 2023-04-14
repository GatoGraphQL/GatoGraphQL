<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Server;

use GraphQLAPI\GraphQLAPI\App;
use GraphQLAPI\GraphQLAPI\Exception\GraphQLServerNotReadyException;
use GraphQLByPoP\GraphQLServer\Server\GraphQLServer;
use GraphQLByPoP\GraphQLServer\Server\GraphQLServerInterface;

/**
 * Obtain a single instance of the GraphQLServer object,
 * initialized with the same configuration as the wp-admin
 * endpoint.
 */
class GraphQLServerFactory
{
    private static ?GraphQLServerInterface $graphQLServer = null;

    /**
     * Create a new instance of the GraphQLServer
     *
     * @throws GraphQLServerNotReadyException If the GraphQL Server is not ready yet
     */
    public static function getInstance(): GraphQLServerInterface
    {
        if (self::$graphQLServer === null) {
            self::$graphQLServer = self::createInstance();
        }
        return self::$graphQLServer;
    }

    /**
     * Create a new instance of the GraphQLServer
     *
     * @throws GraphQLServerNotReadyException If the GraphQL Server is not ready yet
     */
    private static function createInstance(): GraphQLServerInterface
    {
        $appLoader = App::getAppLoader();
        if (!$appLoader->isReadyState()) {
            throw new GraphQLServerNotReadyException();
        }
        return new GraphQLServer();

        /**
         * If we need to use a StandaloneGraphQLServer,
         * then retrieve the configuration (already set
         * by the MainPlugin and all the Extensions) from
         * the current AppLoader.
         *
         * Keeping this code commented as it may prove
         * useful at some point.
         */
        // return new StandaloneGraphQLServer(
        //     $appLoader->getModuleClassesToInitialize(),
        //     $appLoader->getModuleClassConfiguration(),
        //     $appLoader->getSystemContainerCompilerPassClasses(),
        //     $appLoader->getApplicationContainerCompilerPassClasses(),
        //     $appLoader->getContainerCacheConfiguration(),
        // );
    }
}
