<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Facades;

use GraphQLAPI\GraphQLAPI\ConfigurationCache\ContainerCacheConfigurationManager;
use GraphQLAPI\GraphQLAPI\Services\Helpers\EndpointHelpers;
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
     */
    protected static function doGetInstance(): CacheConfigurationManagerInterface
    {
        /**
         * Only this service will be required, and this one
         * will itself not require any other service
         */
        $endpointHelpers = new EndpointHelpers();
        $containerCacheConfigurationManager = static::createContainerCacheConfigurationManager();
        $containerCacheConfigurationManager->setEndpointHelpers($endpointHelpers);
        return $containerCacheConfigurationManager;
    }

    abstract protected static function createContainerCacheConfigurationManager(): ContainerCacheConfigurationManager;
}
