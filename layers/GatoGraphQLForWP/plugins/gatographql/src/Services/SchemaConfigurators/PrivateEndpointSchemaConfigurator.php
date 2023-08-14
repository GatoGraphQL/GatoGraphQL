<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\SchemaConfigurators;

use GatoGraphQL\GatoGraphQL\ModuleResolvers\EndpointFunctionalityModuleResolver;
use GatoGraphQL\GatoGraphQL\Registries\EndpointSchemaConfigurationExecuterRegistryInterface;
use GatoGraphQL\GatoGraphQL\Registries\SchemaConfigurationExecuterRegistryInterface;

class PrivateEndpointSchemaConfigurator extends AbstractEndpointSchemaConfigurator
{
    private ?EndpointSchemaConfigurationExecuterRegistryInterface $endpointSchemaConfigurationExecuterRegistry = null;

    final public function setEndpointSchemaConfigurationExecuterRegistry(EndpointSchemaConfigurationExecuterRegistryInterface $endpointSchemaConfigurationExecuterRegistry): void
    {
        $this->endpointSchemaConfigurationExecuterRegistry = $endpointSchemaConfigurationExecuterRegistry;
    }
    final protected function getEndpointSchemaConfigurationExecuterRegistry(): EndpointSchemaConfigurationExecuterRegistryInterface
    {
        if ($this->endpointSchemaConfigurationExecuterRegistry === null) {
            /** @var EndpointSchemaConfigurationExecuterRegistryInterface */
            $endpointSchemaConfigurationExecuterRegistry = $this->instanceManager->getInstance(EndpointSchemaConfigurationExecuterRegistryInterface::class);
            $this->endpointSchemaConfigurationExecuterRegistry = $endpointSchemaConfigurationExecuterRegistry;
        }
        return $this->endpointSchemaConfigurationExecuterRegistry;
    }

    protected function getEnablingModule(): string
    {
        return EndpointFunctionalityModuleResolver::PRIVATE_ENDPOINT;
    }

    protected function getSchemaConfigurationExecuterRegistry(): SchemaConfigurationExecuterRegistryInterface
    {
        return $this->getEndpointSchemaConfigurationExecuterRegistry();
    }
}
