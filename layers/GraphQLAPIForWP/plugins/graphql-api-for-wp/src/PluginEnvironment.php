<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI;

use GraphQLAPI\GraphQLAPI\StaticHelpers\PluginEnvironmentHelpers;

class PluginEnvironment
{
    public const DISABLE_CACHING = 'DISABLE_CACHING';
    public const CACHE_DIR = 'CACHE_DIR';
    public const ENABLE_UNSAFE_DEFAULTS = 'ENABLE_UNSAFE_DEFAULTS';

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

        if (PluginEnvironmentHelpers::isWPConfigConstantDefined(self::DISABLE_CACHING)) {
            return !PluginEnvironmentHelpers::getWPConfigConstantValue(self::DISABLE_CACHING);
        }

        return true;
    }

    /**
     * If the cache dir is provided by either environment variable
     * or constant in wp-config.php, use it.
     * Otherwise, set the default to wp-content/graphql-api/cache
     */
    public static function getCacheDir(): string
    {
        $baseCacheDir = null;
        if (getenv(self::CACHE_DIR) !== false) {
            $baseCacheDir = rtrim(getenv(self::CACHE_DIR), '/');
        } elseif (PluginEnvironmentHelpers::isWPConfigConstantDefined(self::CACHE_DIR)) {
            $baseCacheDir = rtrim(PluginEnvironmentHelpers::getWPConfigConstantValue(self::CACHE_DIR), '/');
        } else {
            $baseCacheDir = constant('WP_CONTENT_DIR');
        }

        return $baseCacheDir . \DIRECTORY_SEPARATOR . 'graphql-api' . \DIRECTORY_SEPARATOR . 'cache';

        // This is under wp-content/plugins/graphql-api/cache
        // return dirname(__FILE__, 2) . \DIRECTORY_SEPARATOR . 'cache';
    }

    public static function areUnsafeDefaultsEnabled(): bool
    {
        if (getenv(self::ENABLE_UNSAFE_DEFAULTS) !== false) {
            return (bool)getenv(self::ENABLE_UNSAFE_DEFAULTS);
        } elseif (PluginEnvironmentHelpers::isWPConfigConstantDefined(self::ENABLE_UNSAFE_DEFAULTS)) {
            return (bool)PluginEnvironmentHelpers::getWPConfigConstantValue(self::ENABLE_UNSAFE_DEFAULTS);
        }

        return false;
    }
}
