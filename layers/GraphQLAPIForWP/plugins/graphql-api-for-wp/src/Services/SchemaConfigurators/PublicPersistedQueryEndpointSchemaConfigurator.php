<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\EndpointFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Registries\PublicPersistedQueryEndpointSchemaConfigurationExecuterRegistryInterface;
use GraphQLAPI\GraphQLAPI\Registries\SchemaConfigurationExecuterRegistryInterface;

class PublicPersistedQueryEndpointSchemaConfigurator extends AbstractCustomPostEndpointSchemaConfigurator
{
    private ?PublicPersistedQueryEndpointSchemaConfigurationExecuterRegistryInterface $publicPersistedQueryEndpointSchemaConfigurationExecuterRegistry = null;

    final public function setPublicPersistedQueryEndpointSchemaConfigurationExecuterRegistry(PublicPersistedQueryEndpointSchemaConfigurationExecuterRegistryInterface $publicPersistedQueryEndpointSchemaConfigurationExecuterRegistry): void
    {
        $this->publicPersistedQueryEndpointSchemaConfigurationExecuterRegistry = $publicPersistedQueryEndpointSchemaConfigurationExecuterRegistry;
    }
    final protected function getPublicPersistedQueryEndpointSchemaConfigurationExecuterRegistry(): PublicPersistedQueryEndpointSchemaConfigurationExecuterRegistryInterface
    {
        /** @var PublicPersistedQueryEndpointSchemaConfigurationExecuterRegistryInterface */
        return $this->publicPersistedQueryEndpointSchemaConfigurationExecuterRegistry ??= $this->instanceManager->getInstance(PublicPersistedQueryEndpointSchemaConfigurationExecuterRegistryInterface::class);
    }

    /**
     * Only enable the service, if the corresponding module is also enabled
     */
    public function isServiceEnabled(): bool
    {
        return $this->getModuleRegistry()->isModuleEnabled(EndpointFunctionalityModuleResolver::PUBLIC_PERSISTED_QUERIES)
            && parent::isServiceEnabled();
    }

    protected function getSchemaConfigurationExecuterRegistry(): SchemaConfigurationExecuterRegistryInterface
    {
        return $this->getPublicPersistedQueryEndpointSchemaConfigurationExecuterRegistry();
    }
}
