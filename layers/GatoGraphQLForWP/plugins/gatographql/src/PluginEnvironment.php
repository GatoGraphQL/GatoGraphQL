<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL;

use GatoGraphQL\GatoGraphQL\StaticHelpers\PluginEnvironmentHelpers;

class PluginEnvironment
{
    public final const DISABLE_CONTAINER_CACHING = 'DISABLE_CONTAINER_CACHING';
    public final const CACHE_DIR = 'CACHE_DIR';
    public final const SETTINGS_OPTION_ENABLE_RESTRICTIVE_DEFAULT_BEHAVIOR = 'SETTINGS_OPTION_ENABLE_RESTRICTIVE_DEFAULT_BEHAVIOR';

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

        /**
         * Use a static namespace because here we don't have the value
         * set via the PluginInitializationConfiguration
         */
        if (PluginEnvironmentHelpers::isWPConfigConstantDefined(PluginMetadata::WPCONFIG_CONST_NAMESPACE, self::DISABLE_CONTAINER_CACHING)) {
            return !PluginEnvironmentHelpers::getWPConfigConstantValue(PluginMetadata::WPCONFIG_CONST_NAMESPACE, self::DISABLE_CONTAINER_CACHING);
        }

        return true;
    }

    public static function getGatoGraphQLDynamicFileStorageDir(): string
    {
        $baseCacheDir = null;
        if (getenv(self::CACHE_DIR) !== false) {
            $baseCacheDir = rtrim(getenv(self::CACHE_DIR), '/');
        } elseif (PluginEnvironmentHelpers::isWPConfigConstantDefined(PluginMetadata::WPCONFIG_CONST_NAMESPACE, self::CACHE_DIR)) {
            $baseCacheDir = rtrim(PluginEnvironmentHelpers::getWPConfigConstantValue(PluginMetadata::WPCONFIG_CONST_NAMESPACE, self::CACHE_DIR), '/');
        } else {
            $baseCacheDir = constant('WP_CONTENT_DIR');
        }

        return $baseCacheDir . \DIRECTORY_SEPARATOR . 'gatographql';
    }

    /**
     * If the cache dir is provided by either environment variable
     * or constant in wp-config.php, use it.
     * Otherwise, set the default to wp-content/gatographql/cache
     */
    public static function getCacheDir(): string
    {
        return static::getGatoGraphQLDynamicFileStorageDir() . \DIRECTORY_SEPARATOR . 'cache';

        // This is under wp-content/plugins/gatographql/cache
        // return dirname(__FILE__, 2) . \DIRECTORY_SEPARATOR . 'cache';
    }

    public static function getLogsDir(): string
    {
        return static::getGatoGraphQLDynamicFileStorageDir() . \DIRECTORY_SEPARATOR . 'logs';
    }

    public static function getLogsFilePath(string $filename): string
    {
        return static::getLogsDir() . \DIRECTORY_SEPARATOR . $filename;
    }
}
