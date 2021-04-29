<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI;

use GraphQLAPI\GraphQLAPI\Config\PluginConfigurationHelpers;

class PluginEnvironment
{
    public const DISABLE_CACHING = 'DISABLE_CACHING';
    public const CACHE_DIR = 'CACHE_DIR';

    /**
     * If the information is provided by either environment variable
     * or constant in wp-config.php, use it.
     * By default, do cache (also for DEV)
     */
    public static function isCachingEnabled(): bool
    {
        if (getenv(self::DISABLE_CACHING) !== false) {
            return strtolower(getenv(self::DISABLE_CACHING)) != "true";
        }

        if (PluginConfigurationHelpers::isWPConfigConstantDefined(self::DISABLE_CACHING)) {
            return !PluginConfigurationHelpers::getWPConfigConstantValue(self::DISABLE_CACHING);
        }

        return true;
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
