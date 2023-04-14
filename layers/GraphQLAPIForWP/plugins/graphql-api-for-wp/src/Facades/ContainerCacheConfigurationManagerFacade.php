<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Facades;

use GraphQLAPI\GraphQLAPI\ConfigurationCache\ContainerCacheConfigurationManager;

/**
 * Obtain an instance of the ContainerCacheConfigurationManager.
 * Manage the instance internally instead of using the ContainerBuilder,
 * because it is required for setting configuration values before components
 * are initialized, so the ContainerBuilder (for both Sytem/Application)
 * is still unavailable.
 */
class ContainerCacheConfigurationManagerFacade extends AbstractContainerCacheConfigurationManagerFacade
{
    use ContainerCacheConfigurationManagerFacadeTrait;
    
    protected static function createContainerCacheConfigurationManager():  ContainerCacheConfigurationManager
    {
        return new ContainerCacheConfigurationManager();
    }
}
