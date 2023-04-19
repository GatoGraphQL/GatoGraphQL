<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Server;

use GraphQLAPI\GraphQLAPI\App;
use GraphQLAPI\GraphQLAPI\Exception\GraphQLServerNotReadyException;
use GraphQLByPoP\GraphQLServer\Server\GraphQLServerInterface;

trait GraphQLServerFactoryTrait
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
            self::$graphQLServer = static::doGetInstance();
        }
        return self::$graphQLServer;
    }

    /**
     * Obtain the new instance of the GraphQLServer
     *
     * @throws GraphQLServerNotReadyException If the GraphQL Server is not ready yet
     */
    protected static function doGetInstance(): GraphQLServerInterface
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

    /**
     * Create a new instance of the GraphQLServer
     */
    abstract protected static function createInstance(): GraphQLServerInterface;
}
