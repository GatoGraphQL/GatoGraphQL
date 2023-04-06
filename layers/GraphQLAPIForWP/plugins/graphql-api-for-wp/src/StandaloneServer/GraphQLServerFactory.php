<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\StandaloneServer;

use GraphQLAPI\GraphQLAPI\App;
use GraphQLAPI\GraphQLAPI\Exception\GraphQLServerNotReadyException;
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
     *
     * @see layers/Engine/packages/root-wp/src/AppLoader.php
     */
    private static function createInstance(): GraphQLServer
    {
        $appLoader = App::getAppLoader();
        if (!$appLoader->isReadyState()) {
            throw new GraphQLServerNotReadyException(
                sprintf(
                    \__('The GraphQL server is not ready yet. Its initialization takes place in WordPress action hooks: \'%s\' in the wp-admin, \'%s\' in the WP REST API, and \'%s\' otherwise (i.e. in the actual website). Invoke the GraphQL server only after these hooks have been invoked.', 'graphql-api'),
                    'wp_loaded',
                    'rest_api_init',
                    'wp'
                )
            );
        }
        return new GraphQLServer(
            $appLoader->getModuleClassesToInitialize(),
            $appLoader->getModuleClassConfiguration(),
            $appLoader->getSystemContainerCompilerPassClasses(),
            $appLoader->getApplicationContainerCompilerPassClasses(),
            $appLoader->getContainerCacheConfiguration(),
        );
    }
}
