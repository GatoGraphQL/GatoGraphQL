<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI;

use GraphQLAPI\GraphQLAPI\StaticHelpers\PluginEnvironmentHelpers;

class PluginEnvironment
{
    public final const DISABLE_CONTAINER_CACHING = 'DISABLE_CONTAINER_CACHING';
    public final const CACHE_DIR = 'CACHE_DIR';
    public final const SETTINGS_OPTION_ENABLE_UNSAFE_DEFAULT_BEHAVIOR = 'SETTINGS_OPTION_ENABLE_UNSAFE_DEFAULT_BEHAVIOR';

    /**
     * If the information is provided by either environment variable
     * or constant in wp-config.php, use it.
     * By default, do cache (also for DEV)
     */
    public static function isContainerCachingEnabled(): bool
    {
        if (getenv(self::DISABLE_CONTAINER_CACHING) !== false) {
            return strtolower(getenv(self::DISABLE_CONTAINER_CACHING)) !== "true";
        }

        if (PluginEnvironmentHelpers::isWPConfigConstantDefined(self::DISABLE_CONTAINER_CACHING)) {
            return !PluginEnvironmentHelpers::getWPConfigConstantValue(self::DISABLE_CONTAINER_CACHING);
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
}
