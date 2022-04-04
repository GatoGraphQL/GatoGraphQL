<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\AppObjects;

/**
 * Configuration to cache the container
 */
class ContainerCacheConfiguration
{
    public function __construct(
        private readonly bool $cacheContainerConfiguration,
        private readonly ?string $containerConfigurationCacheNamespace,
        private readonly ?string $containerConfigurationCacheDirectory,
    ) {
    }

    public function cacheContainerConfiguration(): bool
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
