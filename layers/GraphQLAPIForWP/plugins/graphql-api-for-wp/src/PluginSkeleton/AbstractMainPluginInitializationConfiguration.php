<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\PluginSkeleton;

use GraphQLAPI\GraphQLAPI\Facades\ContainerCacheConfigurationManagerFacade;
use GraphQLAPI\GraphQLAPI\AppObjects\ContainerCacheConfiguration;

/**
 * Base class to set the configuration for all the PoP components in the main plugin.
 */
abstract class AbstractMainPluginInitializationConfiguration extends AbstractPluginInitializationConfiguration implements MainPluginInitializationConfigurationInterface
{
    /**
     * Cache the Container Cache Configuration
     */
    private ?ContainerCacheConfiguration $containerCacheConfigurationCache = null;

    /**
     * Provide the configuration to cache the container
     */
    public function getContainerCacheConfiguration(): ContainerCacheConfiguration
    {
        if ($this->containerCacheConfigurationCache === null) {
            $containerConfigurationCacheNamespace = null;
            $containerConfigurationCacheDirectory = null;
            if ($cacheContainerConfiguration = $this->isCachingEnabled()) {
                $containerCacheConfigurationManager = ContainerCacheConfigurationManagerFacade::getInstance();
                $containerConfigurationCacheNamespace = $containerCacheConfigurationManager->getNamespace();
                $containerConfigurationCacheDirectory = $containerCacheConfigurationManager->getDirectory();
            }
            $this->containerCacheConfigurationCache = new ContainerCacheConfiguration(
                $cacheContainerConfiguration,
                $containerConfigurationCacheNamespace,
                $containerConfigurationCacheDirectory
            );
        }
        return $this->containerCacheConfigurationCache;
    }

    protected function isCachingEnabled(): bool
    {
        return false;
    }
}
