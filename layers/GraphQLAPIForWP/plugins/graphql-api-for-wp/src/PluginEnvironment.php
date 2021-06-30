<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI;

use GraphQLAPI\GraphQLAPI\Config\PluginConfigurationHelpers;

class PluginEnvironment
{
    public const DISABLE_CACHING = 'DISABLE_CACHING';
    public const CACHE_DIR = 'CACHE_DIR';
    public const REDEFINED_FIELD_ARGS_SYMBOLS = 'REDEFINED_FIELD_ARGS_SYMBOLS';

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

    /**
     * Make it difficult to have the string be considered a field (eg: title()),
     * by changing the field args `()` symbols into something different.
     * 
     * Must pass a string of exactly two characters:
     * 
     *   1st: QuerySyntax::$SYMBOL_FIELDARGS_OPENING (eg: `{`)
     *   2nd: QuerySyntax::$SYMBOL_FIELDARGS_CLOSING (eg: `}`)
     * 
     * Eg: REDEFINED_FIELD_ARGS_SYMBOLS={}
     */
    public static function getRedefinedFieldArgsSymbols(): ?array
    {
        $chars = null;
        if (getenv(self::REDEFINED_FIELD_ARGS_SYMBOLS) !== false) {
            $chars = getenv(self::REDEFINED_FIELD_ARGS_SYMBOLS);
        } elseif (PluginConfigurationHelpers::isWPConfigConstantDefined(self::REDEFINED_FIELD_ARGS_SYMBOLS)) {
            $chars = PluginConfigurationHelpers::getWPConfigConstantValue(self::REDEFINED_FIELD_ARGS_SYMBOLS);
        }
        if ($chars !== null && strlen($chars) === 2) {
            return [$chars[0], $chars[1]];
        }
        return null;
    }
}
