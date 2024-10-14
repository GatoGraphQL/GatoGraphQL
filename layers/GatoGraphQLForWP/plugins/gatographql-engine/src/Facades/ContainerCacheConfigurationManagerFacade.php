<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Facades;

use GatoGraphQL\GatoGraphQL\ConfigurationCache\ContainerCacheConfigurationManager;

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

    /**
     * @param array<string,mixed> $pluginAppGraphQLServerContext
     */
    protected static function createContainerCacheConfigurationManager(array $pluginAppGraphQLServerContext): ContainerCacheConfigurationManager
    {
        return new ContainerCacheConfigurationManager();
    }
}
