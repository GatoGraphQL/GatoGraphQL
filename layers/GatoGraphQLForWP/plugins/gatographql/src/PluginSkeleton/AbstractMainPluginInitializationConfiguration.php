<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\PluginSkeleton;

use GatoGraphQL\GatoGraphQL\Facades\ContainerCacheConfigurationManagerFacade;
use GatoGraphQL\GatoGraphQL\Facades\InternalGraphQLServerContainerCacheConfigurationManagerFacade;
use GatoGraphQL\GatoGraphQL\PluginAppGraphQLServerNames;
use PoP\Root\Container\ContainerCacheConfiguration;
use PoP\Root\Helpers\AppThreadHelpers;
use PoP\Root\Helpers\ClassHelpers;

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

    public function __construct(
        protected MainPluginInterface $mainPlugin
    ) {
    }

    /**
     * Provide the configuration to cache the container
     *
     * @param array<string,mixed> $pluginAppGraphQLServerContext
     */
    public function getContainerCacheConfiguration(
        string $pluginAppGraphQLServerName,
        array $pluginAppGraphQLServerContext,
    ): ContainerCacheConfiguration {
        $pluginAppGraphQLServerUniqueID = AppThreadHelpers::getUniqueID(
            $pluginAppGraphQLServerName,
            $pluginAppGraphQLServerContext,
        );
        if (!isset($this->containerCacheConfigurationsCache[$pluginAppGraphQLServerUniqueID])) {
            $this->containerCacheConfigurationsCache[$pluginAppGraphQLServerUniqueID] = $this->doGetContainerCacheConfiguration(
                $pluginAppGraphQLServerName,
                $pluginAppGraphQLServerContext,
            );
        }
        return $this->containerCacheConfigurationsCache[$pluginAppGraphQLServerUniqueID];
    }

    /**
     * @param array<string,mixed> $pluginAppGraphQLServerContext
     */
    protected function doGetContainerCacheConfiguration(
        string $pluginAppGraphQLServerName,
        array $pluginAppGraphQLServerContext,
    ): ContainerCacheConfiguration {
        $containerConfigurationCacheNamespace = null;
        $containerConfigurationCacheDirectory = null;
        if ($cacheContainerConfiguration = $this->isContainerCachingEnabled()) {
            /**
             * The internal server has a different configuration,
             * and must be cached on its own file.
             */
            if ($pluginAppGraphQLServerName === PluginAppGraphQLServerNames::INTERNAL) {
                $containerCacheConfigurationManager = InternalGraphQLServerContainerCacheConfigurationManagerFacade::getInstance($pluginAppGraphQLServerContext);
            } else {
                $containerCacheConfigurationManager = ContainerCacheConfigurationManagerFacade::getInstance($pluginAppGraphQLServerContext);
            }
            $containerConfigurationCacheNamespace = $containerCacheConfigurationManager->getNamespace();
            $containerConfigurationCacheDirectory = $containerCacheConfigurationManager->getDirectory();
        }
        return new ContainerCacheConfiguration(
            ClassHelpers::getTrailingClassPSR4Namespace(get_class($this->mainPlugin)),
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
