<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\StandaloneServer;

use GraphQLAPI\GraphQLAPI\App;
use GraphQLByPoP\GraphQLServer\Standalone\GraphQLServer;
use PoP\Root\Module\ModuleInterface;

/**
 * Obtain a single instance of the GraphQLServer object,
 * initialized with the same configuration as the wp-admin
 * endpoint.
 */
class GraphQLServerFactory
{
    private static ?GraphQLServer $graphQLServer = null;

    public static function getInstance(): GraphQLServer
    {
        if (self::$graphQLServer === null) {
            self::$graphQLServer = static::createInstance();
        }
        return self::$graphQLServer;
    }
    
    private static function createInstance(): GraphQLServer
    {
        return new GraphQLServer(
            App::getAppLoader()->getModuleClassesToInitialize(),
            static::getModuleClassConfiguration(),
            [],
            [],
            false,
        );
    }

    /**
     * @return array<class-string<ModuleInterface>>
     */
    private static function getModuleClasses(): array
    {
        return [];
    }

    /**
     * @return array<class-string<ModuleInterface>,array<string,mixed>> [key]: Module class, [value]: Configuration
     */
    private static function getModuleClassConfiguration(): array
    {
        return [];
    }
}
