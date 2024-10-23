<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL;

use GatoGraphQL\GatoGraphQL\StaticHelpers\PluginEnvironmentHelpers;

class PluginEnvironment
{
    public final const DISABLE_CONTAINER_CACHING = 'DISABLE_CONTAINER_CACHING';
    public final const CONTAINER_CACHE_DIR = 'CONTAINER_CACHE_DIR';
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

    public static function getGatoGraphQLDynamicFileStorageDir(
        string $dirName = 'gatographql',
    ): string {
        $baseCacheDir = null;
        if (getenv(self::CONTAINER_CACHE_DIR) !== false) {
            $baseCacheDir = rtrim(getenv(self::CONTAINER_CACHE_DIR), '/');
        } elseif (PluginEnvironmentHelpers::isWPConfigConstantDefined(PluginMetadata::WPCONFIG_CONST_NAMESPACE, self::CONTAINER_CACHE_DIR)) {
            $baseCacheDir = rtrim(PluginEnvironmentHelpers::getWPConfigConstantValue(PluginMetadata::WPCONFIG_CONST_NAMESPACE, self::CONTAINER_CACHE_DIR), '/');
        } else {
            $baseCacheDir = constant('WP_CONTENT_DIR');
        }

        return $baseCacheDir . \DIRECTORY_SEPARATOR . $dirName;
    }

    /**
     * If the cache dir is provided by either environment variable
     * or constant in wp-config.php, use it.
     * Otherwise, set the default to wp-content/gatographql/cache
     *
     * This method is invoked when initializing the plugin, before
     * the main Plugin class is registered. Then, the folder cannot
     * be inject via the Plugin, and the static "gatographql" must
     * always be used.
     */
    public static function getCacheDir(): string
    {
        return static::getGatoGraphQLDynamicFileStorageDir() . \DIRECTORY_SEPARATOR . 'cache';

        // This is under wp-content/plugins/gatographql/cache
        // return dirname(__FILE__, 2) . \DIRECTORY_SEPARATOR . 'cache';
    }

    public static function getLogsDir(): string
    {
        $dirName = PluginApp::getMainPlugin()->getPluginWPContentFolderName();
        return static::getGatoGraphQLDynamicFileStorageDir($dirName) . \DIRECTORY_SEPARATOR . 'logs';
    }

    public static function getLogsFilePath(string $filename): string
    {
        return static::getLogsDir() . \DIRECTORY_SEPARATOR . $filename;
    }
}
