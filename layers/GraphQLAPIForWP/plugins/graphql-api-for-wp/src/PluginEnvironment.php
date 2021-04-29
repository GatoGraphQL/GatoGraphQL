<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI;

use GraphQLAPI\GraphQLAPI\Config\PluginConfigurationHelpers;
use PoP\Root\Environment;

class PluginEnvironment
{
    public const CACHE_CONTAINERS = 'CACHE_CONTAINERS';
    public const CACHE_DIR = 'CACHE_DIR';

    /**
     * By default, do not cache for DEV, cache otherwise
     */
    public static function cacheContainers(): bool
    {
        return getenv(self::CACHE_CONTAINERS) !== false ?
            strtolower(getenv(self::CACHE_CONTAINERS)) == "true"
            : !Environment::isApplicationEnvironmentDev();
    }

    /**
     * If the cache dir is provided by either environment variable
     * or constant in wp-config.php, use it.
     * Otherwise, set the default to wp-content/plugins/graphql-api/cache
     */
    public static function getCacheDir(): string
    {
        if (getenv(self::CACHE_DIR) !== false) {
            return rtrim(getenv(self::CACHE_DIR), '/');
        }

        if (PluginConfigurationHelpers::isWPConfigConstantDefined(self::CACHE_DIR)) {
            return rtrim(PluginConfigurationHelpers::getWPConfigConstantValue(self::CACHE_DIR), '/');
        }
        
        return dirname(__FILE__, 2) . \DIRECTORY_SEPARATOR . 'cache';
    }
}
