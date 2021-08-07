<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI;

use GraphQLAPI\GraphQLAPI\Constants\ApplicationNature;
use GraphQLAPI\GraphQLAPI\PluginManagement\PluginConfigurationHelper;

class PluginEnvironment
{
    public const DISABLE_CACHING = 'DISABLE_CACHING';
    public const CACHE_DIR = 'CACHE_DIR';
    public const APPLICATION_NATURE = 'APPLICATION_NATURE';

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

        if (PluginConfigurationHelper::isWPConfigConstantDefined(self::DISABLE_CACHING)) {
            return !PluginConfigurationHelper::getWPConfigConstantValue(self::DISABLE_CACHING);
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
        } elseif (PluginConfigurationHelper::isWPConfigConstantDefined(self::CACHE_DIR)) {
            $baseCacheDir = rtrim(PluginConfigurationHelper::getWPConfigConstantValue(self::CACHE_DIR), '/');
        } else {
            $baseCacheDir = constant('WP_CONTENT_DIR');
        }

        return $baseCacheDir . \DIRECTORY_SEPARATOR . 'graphql-api' . \DIRECTORY_SEPARATOR . 'cache';

        // This is under wp-content/plugins/graphql-api/cache
        // return dirname(__FILE__, 2) . \DIRECTORY_SEPARATOR . 'cache';
    }

    /**
     * The nature is either "static" or "live".
     * From it, we can provide the default settings, being either safer or looser
     */
    public static function getApplicationNature(): string
    {
        $definedNature = null;
        if (getenv(self::APPLICATION_NATURE) !== false) {
            $definedNature = trim(getenv(self::APPLICATION_NATURE));
        } elseif (PluginConfigurationHelper::isWPConfigConstantDefined(self::APPLICATION_NATURE)) {
            $definedNature = trim(PluginConfigurationHelper::getWPConfigConstantValue(self::APPLICATION_NATURE));
        }

        if (
            in_array($definedNature, [
            ApplicationNature::STATIC_,
            ApplicationNature::LIVE,
            ])
        ) {
            return $definedNature;
        }

        return ApplicationNature::LIVE;
    }

    /**
     * Indicate if the application is intended for building "static" sites
     */
    public static function isApplicationNatureStatic(): bool
    {
        return self::getApplicationNature() === ApplicationNature::STATIC_;
    }
}
