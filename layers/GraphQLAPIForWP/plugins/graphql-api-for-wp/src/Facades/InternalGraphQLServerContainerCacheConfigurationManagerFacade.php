<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Facades;

use GraphQLAPI\GraphQLAPI\ConfigurationCache\ContainerCacheConfigurationManager;
use GraphQLAPI\GraphQLAPI\ConfigurationCache\InternalGraphQLServerContainerCacheConfigurationManager;

/**
 * Obtain an instance of the InternalGraphQLServerContainerCacheConfigurationManager.
 * Manage the instance internally instead of using the ContainerBuilder,
 * because it is required for setting configuration values before components
 * are initialized, so the ContainerBuilder (for both Sytem/Application)
 * is still unavailable.
 */
class InternalGraphQLServerContainerCacheConfigurationManagerFacade extends AbstractContainerCacheConfigurationManagerFacade
{
    use ContainerCacheConfigurationManagerFacadeTrait;

    protected static function createContainerCacheConfigurationManager(): ContainerCacheConfigurationManager
    {
        return new InternalGraphQLServerContainerCacheConfigurationManager();
    }
}
