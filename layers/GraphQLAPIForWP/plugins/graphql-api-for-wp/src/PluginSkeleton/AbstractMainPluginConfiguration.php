<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\PluginSkeleton;

use GraphQLAPI\GraphQLAPI\Facades\CacheConfigurationManagerFacade;
use GraphQLAPI\GraphQLAPI\PluginManagement\MainPluginManager;

/**
 * Base class to set the configuration for all the PoP components in the main plugin.
 */
abstract class AbstractMainPluginConfiguration extends AbstractPluginConfiguration
{
    /**
     * Cache the Container Cache Configuration
     *
     * @var array<mixed> Array with args to pass to `AppLoader::initializeContainers` - [0]: cache container? (bool), [1]: container namespace (string|null)
     */
    protected ?array $containerCacheConfigurationCache = null;

    /**
     * Provide the configuration to cache the container
     *
     * @return array<mixed> Array with args to pass to `AppLoader::initializeContainers`:
     *                      [0]: cache container? (bool)
     *                      [1]: container namespace (string|null)
     *                      [2]: container directory (string|null)
     */
    public function getContainerCacheConfiguration(): array
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
            $this->containerCacheConfigurationCache = [
                $cacheContainerConfiguration,
                $containerConfigurationCacheNamespace,
                $containerConfigurationCacheDirectory
            ];
        }
        return $this->containerCacheConfigurationCache;
    }

    protected function isCachingEnabled(): bool
    {
        return false;
    }
}
