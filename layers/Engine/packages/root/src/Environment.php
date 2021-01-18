<?php

declare(strict_types=1);

namespace PoP\Root;

class Environment
{
    public const CACHE_CONTAINER_CONFIGURATION = 'CACHE_CONTAINER_CONFIGURATION';
    public const CONTAINER_CONFIGURATION_CACHE_NAMESPACE = 'CONTAINER_CONFIGURATION_CACHE_NAMESPACE';
    public const THROW_EXCEPTION_IF_CACHE_SETUP_ERROR = 'THROW_EXCEPTION_IF_CACHE_SETUP_ERROR';

    /**
     * Indicate if to cache the container configuration.
     * Using `getenv` instead of $_ENV because this latter one, somehow, doesn't work yet:
     * Because this code is executed to know from where to load the container configuration,
     * this env variable can't be configured using the .env file, must must be injected
     * straight into the webserver.
     * By default, do not cache, since that's the conservative approach that always works.
     * Otherwise, if caching, newly installed modules (eg: on WordPress plugin) may not work
     *
     * @return boolean
     */
    public static function cacheContainerConfiguration(): bool
    {
        // If the environment variable is not set, `getenv` returns the boolean `false`
        // Otherwise, it returns the string value
        $useCache = getenv(self::CACHE_CONTAINER_CONFIGURATION);
        return $useCache !== false ? strtolower($useCache) == "true" : false;
    }

    /**
     * By default, use the SERVER_NAME + application version
     */
    public static function getCacheContainerConfigurationNamespace(): ?string
    {
        if (getenv(self::CONTAINER_CONFIGURATION_CACHE_NAMESPACE) !== false) {
            return getenv(self::CONTAINER_CONFIGURATION_CACHE_NAMESPACE);
        }
        /**
         * SERVER_NAME is used for if several applications are deployed
         * on the same server and they share the /tmp folder
         */
        $sitename = strtolower($_SERVER['SERVER_NAME'] ?? '');
        if ($applicationVersion = self::getApplicationVersion()) {
            return $sitename . '_' . $applicationVersion;
        }
        return $sitename;
    }

    /**
     * Define behavior when the server can't create set-up the cache,
     * (for instance if creating the directory fails):
     *
     * 1. `true` => throw an exception
     * 2. `false` => ignore, and simply use no cache
     *
     * @see https://github.com/leoloso/PoP/issues/350
     */
    public static function throwExceptionIfCacheSetupError(): bool
    {
        $throwException = getenv(self::THROW_EXCEPTION_IF_CACHE_SETUP_ERROR);
        return $throwException !== false ? strtolower($throwException) == "true" : false;
    }

    /**
     * Provide the application version, definable by env var
     */
    public static function getApplicationVersion(): ?string
    {
        return getenv('APPLICATION_VERSION') !== false ? getenv('APPLICATION_VERSION') : null;
    }
}
