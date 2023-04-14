<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\PluginSkeleton;

use GraphQLAPI\GraphQLAPI\ConfigurationCache\ContainerCacheConfigurationManager;
use GraphQLAPI\GraphQLAPI\Facades\ContainerCacheConfigurationManagerFacade;
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
            $containerConfigurationCacheNamespace = null;
            $containerConfigurationCacheDirectory = null;
            if ($cacheContainerConfiguration = $this->isContainerCachingEnabled()) {
                /** @var ContainerCacheConfigurationManager */
                $containerCacheConfigurationManager = ContainerCacheConfigurationManagerFacade::getInstance();
                /**
                 * The internal server is always private, and has the
                 * same configuration as the default admin endpoint.
                 */
                if ($pluginAppGraphQLServerName === PluginAppGraphQLServerNames::INTERNAL) {
                    $containerConfigurationCacheNamespace = $containerCacheConfigurationManager->getInternalGraphQLServerNamespace();
                } else {
                    $containerConfigurationCacheNamespace = $containerCacheConfigurationManager->getNamespace();
                }
                $containerConfigurationCacheDirectory = $containerCacheConfigurationManager->getDirectory();
            }
            $this->containerCacheConfigurationsCache[$pluginAppGraphQLServerName] = new ContainerCacheConfiguration(
                $cacheContainerConfiguration,
                $containerConfigurationCacheNamespace,
                $containerConfigurationCacheDirectory
            );
        }
        return $this->containerCacheConfigurationsCache[$pluginAppGraphQLServerName];
    }

    protected function isContainerCachingEnabled(): bool
    {
        return false;
    }
}
