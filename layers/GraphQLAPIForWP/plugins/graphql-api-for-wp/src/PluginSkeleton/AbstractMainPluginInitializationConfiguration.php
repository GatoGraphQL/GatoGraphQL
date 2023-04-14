<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\PluginSkeleton;

use GraphQLAPI\GraphQLAPI\Facades\ContainerCacheConfigurationManagerFacade;
use GraphQLAPI\GraphQLAPI\Facades\InternalGraphQLServerContainerCacheConfigurationManagerFacade;
use GraphQLAPI\GraphQLAPI\PluginAppGraphQLServerNames;
use PoP\Root\Container\ContainerCacheConfiguration;

/**
 * Base class to set the configuration for all the PoP components in the main plugin.
 */
abstract class AbstractMainPluginInitializationConfiguration extends AbstractPluginInitializationConfiguration implements MainPluginInitializationConfigurationInterface
{
    /**
     * Cache the Container Cache Configuration
     *
     * @var array<string,ContainerCacheConfiguration>
     */
    private array $containerCacheConfigurationsCache = [];

    /**
     * Provide the configuration to cache the container
     */
    public function getContainerCacheConfiguration(
        string $pluginAppGraphQLServerName,
    ): ContainerCacheConfiguration {
        if (!isset($this->containerCacheConfigurationsCache[$pluginAppGraphQLServerName])) {
            $this->containerCacheConfigurationsCache[$pluginAppGraphQLServerName] = $this->doGetContainerCacheConfiguration($pluginAppGraphQLServerName);
        }
        return $this->containerCacheConfigurationsCache[$pluginAppGraphQLServerName];
    }

    protected function doGetContainerCacheConfiguration(
        string $pluginAppGraphQLServerName,
    ): ContainerCacheConfiguration {
        $containerConfigurationCacheNamespace = null;
        $containerConfigurationCacheDirectory = null;
        if ($cacheContainerConfiguration = $this->isContainerCachingEnabled()) {
            /**
             * The internal server has a different configuration,
             * and must be cached on its own file.
             */
            if ($pluginAppGraphQLServerName === PluginAppGraphQLServerNames::INTERNAL) {
                $containerCacheConfigurationManager = InternalGraphQLServerContainerCacheConfigurationManagerFacade::getInstance();
            } else {
                $containerCacheConfigurationManager = ContainerCacheConfigurationManagerFacade::getInstance();
            }
            $containerConfigurationCacheNamespace = $containerCacheConfigurationManager->getNamespace();
            $containerConfigurationCacheDirectory = $containerCacheConfigurationManager->getDirectory();
        }
        return new ContainerCacheConfiguration(
            $cacheContainerConfiguration,
            $containerConfigurationCacheNamespace,
            $containerConfigurationCacheDirectory
        );
    }

    protected function isContainerCachingEnabled(): bool
    {
        return false;
    }
}
