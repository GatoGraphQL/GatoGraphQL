<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\PluginSkeleton;

use PoP\Root\Container\ContainerCacheConfiguration;

interface MainPluginInitializationConfigurationInterface extends PluginInitializationConfigurationInterface
{
    /**
     * Provide the configuration to cache the container
     *
     * @param array<string,mixed> $pluginAppGraphQLServerContext
     */
    public function getContainerCacheConfiguration(
        string $pluginAppGraphQLServerName,
        array $pluginAppGraphQLServerContext,
    ): ContainerCacheConfiguration;
}
