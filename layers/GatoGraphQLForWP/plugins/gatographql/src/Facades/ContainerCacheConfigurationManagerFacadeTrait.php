<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Facades;

use GatoGraphQL\GatoGraphQL\Facades\StateManagers\AppThreadServiceFacade;
use PoP\ComponentModel\Cache\CacheConfigurationManagerInterface;

trait ContainerCacheConfigurationManagerFacadeTrait
{
    /**
     * @var array<string,CacheConfigurationManagerInterface> instances organized by context ID
     */
    protected static array $instances = [];

    /**
     * @param array<string,mixed> $pluginAppGraphQLServerContext
     */
    public static function getInstance(array $pluginAppGraphQLServerContext): CacheConfigurationManagerInterface
    {
        $appThreadService = AppThreadServiceFacade::getInstance();
        $graphQLServerContextUniqueID = $appThreadService->getGraphQLServerContextUniqueID($pluginAppGraphQLServerContext);
        if (!isset(self::$instances[$graphQLServerContextUniqueID])) {
            self::$instances[$graphQLServerContextUniqueID] = self::doGetInstance($pluginAppGraphQLServerContext);
        }
        return self::$instances[$graphQLServerContextUniqueID];
    }

    /**
     * We can't use the InstanceManager, since at this stage
     * it hasn't been initialized yet.
     * We can create a new instances of these classes
     * because their instantiation produces no side-effects
     * (maybe that happens under `initialize`)
     *
     * @param array<string,mixed> $pluginAppGraphQLServerContext
     */
    abstract protected static function doGetInstance(array $pluginAppGraphQLServerContext): CacheConfigurationManagerInterface;
}
