<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\StandaloneServer;

use GraphQLAPI\GraphQLAPI\App;
use GraphQLAPI\GraphQLAPI\Exception\GraphQLServerNotReadyException;
use GraphQLAPI\GraphQLAPI\PluginSkeleton\PluginLifecyclePriorities;
use GraphQLByPoP\GraphQLServer\Standalone\GraphQLServer;

/**
 * Obtain a single instance of the GraphQLServer object,
 * initialized with the same configuration as the wp-admin
 * endpoint.
 */
class GraphQLServerFactory
{
    private static ?GraphQLServer $graphQLServer = null;

    /**
     * Create a new instance of the GraphQLServer
     *
     * @throws GraphQLServerNotReadyException if the application is not ready yet
     */
    public static function getInstance(): GraphQLServer
    {
        if (self::$graphQLServer === null) {
            self::$graphQLServer = static::createInstance();
        }
        return self::$graphQLServer;
    }
    
    /**
     * Create a new instance of the GraphQLServer
     *
     * @throws GraphQLServerNotReadyException if the application is not ready yet
     */
    private static function createInstance(): GraphQLServer
    {
        $appLoader = App::getAppLoader();
        // var_dump($appLoader->getModuleClassConfiguration());die;
        if (!$appLoader->isReadyState()) {
            throw new GraphQLServerNotReadyException(
                sprintf(
                    \__('The GraphQL server is not yet ready. It can be invoked only after hook \'%s\' with priority \'%s\' has taken place', 'graphql-api'),
                    'plugins_loaded',
                    PluginLifecyclePriorities::READY_STATE
                )
            );
        }
        return new GraphQLServer(
            $appLoader->getModuleClassesToInitialize(),
            $appLoader->getModuleClassConfiguration(),
            $appLoader->getSystemContainerCompilerPassClasses(),
            $appLoader->getApplicationContainerCompilerPassClasses(),
            false,
        );
    }
}
