<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Server;

use GraphQLAPI\GraphQLAPI\App;
use GraphQLAPI\GraphQLAPI\Exception\GraphQLServerNotReadyException;
use GraphQLByPoP\GraphQLServer\Server\GraphQLServerInterface;

/**
 * Obtain a single instance of the GraphQLServer object,
 * initialized with the admin configuration, independently
 * if we're currently executing a public endpoint, private
 * endpoint, or no endpoint at all.
 */
class InternalGraphQLServerFactory
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
        if (!App::isInitialized()) {
            throw new GraphQLServerNotReadyException();
        }

        $appLoader = App::getAppLoader();
        if (!$appLoader->isReadyState()) {
            throw new GraphQLServerNotReadyException();
        }

        return new InternalGraphQLServer();
    }
}
