<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Facades;

use GatoGraphQL\GatoGraphQL\ConfigurationCache\ContainerCacheConfigurationManager;
use GatoGraphQL\GatoGraphQL\Services\Helpers\EndpointHelpers;
use PoP\ComponentModel\Cache\CacheConfigurationManagerInterface;

/**
 * Obtain an instance of the ContainerCacheConfigurationManager.
 * Manage the instance internally instead of using the ContainerBuilder,
 * because it is required for setting configuration values before components
 * are initialized, so the ContainerBuilder (for both Sytem/Application)
 * is still unavailable.
 */
abstract class AbstractContainerCacheConfigurationManagerFacade
{
    /**
     * We can't use the InstanceManager, since at this stage
     * it hasn't been initialized yet.
     * We can create a new instance of these classes
     * because their instantiation produces no side-effects
     * (maybe that happens under `initialize`)
     *
     * @param array<string,mixed> $pluginAppGraphQLServerContext
     */
    protected static function doGetInstance(array $pluginAppGraphQLServerContext): CacheConfigurationManagerInterface
    {
        /**
         * Only this service will be required, and this one
         * will itself not require any other service
         */
        $endpointHelpers = new EndpointHelpers();
        $containerCacheConfigurationManager = static::createContainerCacheConfigurationManager($pluginAppGraphQLServerContext);
        $containerCacheConfigurationManager->setEndpointHelpers($endpointHelpers);
        return $containerCacheConfigurationManager;
    }

    /**
     * @param array<string,mixed> $pluginAppGraphQLServerContext
     */
    abstract protected static function createContainerCacheConfigurationManager(array $pluginAppGraphQLServerContext): ContainerCacheConfigurationManager;
}
