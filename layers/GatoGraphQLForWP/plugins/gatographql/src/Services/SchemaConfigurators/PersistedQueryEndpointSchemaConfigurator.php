<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\SchemaConfigurators;

use GatoGraphQL\GatoGraphQL\ModuleResolvers\EndpointFunctionalityModuleResolver;
use GatoGraphQL\GatoGraphQL\Registries\PersistedQueryEndpointSchemaConfigurationExecuterRegistryInterface;
use GatoGraphQL\GatoGraphQL\Registries\SchemaConfigurationExecuterRegistryInterface;

class PersistedQueryEndpointSchemaConfigurator extends AbstractEndpointSchemaConfigurator
{
    private ?PersistedQueryEndpointSchemaConfigurationExecuterRegistryInterface $persistedQueryEndpointSchemaConfigurationExecuterRegistry = null;

    final public function setPersistedQueryEndpointSchemaConfigurationExecuterRegistry(PersistedQueryEndpointSchemaConfigurationExecuterRegistryInterface $persistedQueryEndpointSchemaConfigurationExecuterRegistry): void
    {
        $this->persistedQueryEndpointSchemaConfigurationExecuterRegistry = $persistedQueryEndpointSchemaConfigurationExecuterRegistry;
    }
    final protected function getPersistedQueryEndpointSchemaConfigurationExecuterRegistry(): PersistedQueryEndpointSchemaConfigurationExecuterRegistryInterface
    {
        if ($this->persistedQueryEndpointSchemaConfigurationExecuterRegistry === null) {
            /** @var PersistedQueryEndpointSchemaConfigurationExecuterRegistryInterface */
            $persistedQueryEndpointSchemaConfigurationExecuterRegistry = $this->instanceManager->getInstance(PersistedQueryEndpointSchemaConfigurationExecuterRegistryInterface::class);
            $this->persistedQueryEndpointSchemaConfigurationExecuterRegistry = $persistedQueryEndpointSchemaConfigurationExecuterRegistry;
        }
        return $this->persistedQueryEndpointSchemaConfigurationExecuterRegistry;
    }

    protected function getEnablingModule(): string
    {
        return EndpointFunctionalityModuleResolver::PERSISTED_QUERIES;
    }

    protected function getSchemaConfigurationExecuterRegistry(): SchemaConfigurationExecuterRegistryInterface
    {
        return $this->getPersistedQueryEndpointSchemaConfigurationExecuterRegistry();
    }
}
