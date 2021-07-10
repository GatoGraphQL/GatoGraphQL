<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\PluginSkeleton;

use GraphQLAPI\GraphQLAPI\Facades\CacheConfigurationManagerFacade;
use GraphQLAPI\GraphQLAPI\GetterSetterObjects\ContainerCacheConfiguration;
use GraphQLAPI\GraphQLAPI\PluginManagement\MainPluginManager;

/**
 * Base class to set the configuration for all the PoP components in the main plugin.
 */
abstract class AbstractMainPluginConfiguration extends AbstractPluginConfiguration
{
    /**
     * Cache the Container Cache Configuration
     */
    protected ?ContainerCacheConfiguration $containerCacheConfigurationCache = null;

    /**
     * Provide the configuration to cache the container
     */
    public function getContainerCacheConfiguration(): ContainerCacheConfiguration
    {
        if ($this->containerCacheConfigurationCache === null) {
            $containerConfigurationCacheNamespace = null;
            $containerConfigurationCacheDirectory = null;
            $mainPluginCacheDir = (string) MainPluginManager::getConfig('cache-dir');
            if ($cacheContainerConfiguration = $this->isCachingEnabled()) {
                $cacheConfigurationManager = CacheConfigurationManagerFacade::getInstance();
                $containerConfigurationCacheNamespace = $cacheConfigurationManager->getNamespace();
                $containerConfigurationCacheDirectory = $mainPluginCacheDir . \DIRECTORY_SEPARATOR . 'service-containers';
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
