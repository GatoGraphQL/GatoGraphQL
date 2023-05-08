<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Server;

use GraphQLByPoP\GraphQLServer\Server\GraphQLServerInterface;

trait GraphQLServerFactoryTrait
{
    private static ?GraphQLServerInterface $graphQLServer = null;

    /**
     * Create a new instance of the GraphQLServer
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
     */
    protected static function doGetInstance(): GraphQLServerInterface
    {
        return new InternalGraphQLServer();
    }

    /**
     * Create a new instance of the GraphQLServer
     */
    abstract protected static function createInstance(): GraphQLServerInterface;
}
