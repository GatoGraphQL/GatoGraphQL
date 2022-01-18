<?php

declare(strict_types=1);

namespace PoP\Root;

class Environment
{
    public const CACHE_CONTAINER_CONFIGURATION = 'CACHE_CONTAINER_CONFIGURATION';
    public const CONTAINER_CONFIGURATION_CACHE_NAMESPACE = 'CONTAINER_CONFIGURATION_CACHE_NAMESPACE';
    public const CONTAINER_CONFIGURATION_CACHE_DIRECTORY = 'CONTAINER_CONFIGURATION_CACHE_DIRECTORY';
    public const THROW_EXCEPTION_IF_CACHE_SETUP_ERROR = 'THROW_EXCEPTION_IF_CACHE_SETUP_ERROR';
    public const APPLICATION_VERSION = 'APPLICATION_VERSION';
    public const ENABLE_PASSING_STATE_VIA_REQUEST = 'ENABLE_PASSING_STATE_VIA_REQUEST';
    public const ENABLE_PASSING_ROUTING_STATE_VIA_REQUEST = 'ENABLE_PASSING_ROUTING_STATE_VIA_REQUEST';

    /**
     * Environment
     */
    public const APPLICATION_ENVIRONMENT = 'APPLICATION_ENVIRONMENT';
    /**
     * The app runs in PROD
     */
    public const APPLICATION_ENVIRONMENT_PROD = 'production';
    /**
     * The app runs in DEV
     */
    public const APPLICATION_ENVIRONMENT_DEV = 'development';

    /**
     * Indicate if to cache the container configuration.
     * Using `getenv` instead of $_ENV because this latter one, somehow, doesn't work yet:
     * Because this code is executed to know from where to load the container configuration,
     * this env variable can't be configured using the .env file, must must be injected
     * straight into the webserver.
     * If not defined, do not cache if the environment is DEV, cache otherwise
     */
    public static function cacheContainerConfiguration(): bool
    {
        $useCache = getenv(self::CACHE_CONTAINER_CONFIGURATION);
        return $useCache !== false ? strtolower($useCache) == "true" : !self::isApplicationEnvironmentDev();
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

    public static function getCacheContainerConfigurationDirectory(): ?string
    {
        if (getenv(self::CONTAINER_CONFIGURATION_CACHE_DIRECTORY) !== false) {
            return getenv(self::CONTAINER_CONFIGURATION_CACHE_DIRECTORY);
        }
        return null;
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
        return getenv(self::APPLICATION_VERSION) !== false ? getenv(self::APPLICATION_VERSION) : null;
    }

    /**
     * By default it is PROD. For DEV we must set the env var
     */
    public static function getApplicationEnvironment(): string
    {
        $default = self::APPLICATION_ENVIRONMENT_PROD;
        $environment = getenv(self::APPLICATION_ENVIRONMENT) !== false ? getenv(self::APPLICATION_ENVIRONMENT) : $default;
        $environments = [
            self::APPLICATION_ENVIRONMENT_PROD,
            self::APPLICATION_ENVIRONMENT_DEV,
        ];
        return in_array($environment, $environments) ? $environment : $default;
    }

    public static function isApplicationEnvironmentProd(): bool
    {
        return self::getApplicationEnvironment() == self::APPLICATION_ENVIRONMENT_PROD;
    }

    public static function isApplicationEnvironmentDev(): bool
    {
        return self::getApplicationEnvironment() == self::APPLICATION_ENVIRONMENT_DEV;
    }
}
