<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI;

use PoP\Root\Environment;

class PluginEnvironment
{
    public const CACHE_CONTAINERS = 'CACHE_CONTAINERS';

    /**
     * By default, cache for PROD, do not cache for DEV
     */
    public static function cacheContainers(): bool
    {
        return getenv(self::CACHE_CONTAINERS) !== false ?
            strtolower(getenv(self::CACHE_CONTAINERS)) == "true"
            : Environment::isApplicationEnvironmentProd();
    }
}
