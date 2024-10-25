<?php

declare(strict_types=1);

namespace PoP\Root\Container;

/**
 * Configuration to cache the container
 */
class ContainerCacheConfiguration
{
    /**
     * @param string $applicationName Needed to store the container with a unique classname, to avoid conflict whenever 2 or more applications (each with its own container caching) are running.
     * @param boolean|null $cacheContainerConfiguration Indicate if to cache the container. If null, it gets the value from ENV
     * @param string|null $containerConfigurationCacheNamespace Provide the namespace, to regenerate the cache whenever the application is upgraded. If null, it gets the value from ENV
     * @param string|null $containerConfigurationCacheDirectory Provide the directory, to regenerate the cache whenever the application is upgraded. If null, it uses the default /tmp folder by the OS
     */
    public function __construct(
        private readonly string $applicationName,
        private readonly ?bool $cacheContainerConfiguration = null,
        private readonly ?string $containerConfigurationCacheNamespace = null,
        private readonly ?string $containerConfigurationCacheDirectory = null,
    ) {
    }

    public function getApplicationName(): string
    {
        return $this->applicationName;
    }

    public function cacheContainerConfiguration(): ?bool
    {
        return $this->cacheContainerConfiguration;
    }

    public function getContainerConfigurationCacheNamespace(): ?string
    {
        return $this->containerConfigurationCacheNamespace;
    }

    public function getContainerConfigurationCacheDirectory(): ?string
    {
        return $this->containerConfigurationCacheDirectory;
    }
}
