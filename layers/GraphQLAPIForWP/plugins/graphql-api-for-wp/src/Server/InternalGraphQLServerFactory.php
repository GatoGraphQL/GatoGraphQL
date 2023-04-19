<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Server;

use GraphQLByPoP\GraphQLServer\Server\GraphQLServerInterface;

/**
 * Obtain a single instance of the GraphQLServer object,
 * initialized with the admin configuration, independently
 * if we're currently executing a public endpoint, private
 * endpoint, or no endpoint at all.
 */
class InternalGraphQLServerFactory
{
    use GraphQLServerFactoryTrait;

    /**
     * Create a new instance of the GraphQLServer
     */
    protected static function createInstance(): GraphQLServerInterface
    {
        return new InternalGraphQLServer();
    }
}
