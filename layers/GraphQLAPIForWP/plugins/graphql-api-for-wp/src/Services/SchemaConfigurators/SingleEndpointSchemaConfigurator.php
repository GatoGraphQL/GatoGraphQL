<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\EndpointFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Registries\EndpointSchemaConfigurationExecuterRegistryInterface;
use GraphQLAPI\GraphQLAPI\Registries\SchemaConfigurationExecuterRegistryInterface;

class SingleEndpointSchemaConfigurator extends AbstractEndpointSchemaConfigurator
{
    private ?EndpointSchemaConfigurationExecuterRegistryInterface $endpointSchemaConfigurationExecuterRegistry = null;

    final public function setEndpointSchemaConfigurationExecuterRegistry(EndpointSchemaConfigurationExecuterRegistryInterface $endpointSchemaConfigurationExecuterRegistry): void
    {
        $this->endpointSchemaConfigurationExecuterRegistry = $endpointSchemaConfigurationExecuterRegistry;
    }
    final protected function getEndpointSchemaConfigurationExecuterRegistry(): EndpointSchemaConfigurationExecuterRegistryInterface
    {
        /** @var EndpointSchemaConfigurationExecuterRegistryInterface */
        return $this->endpointSchemaConfigurationExecuterRegistry ??= $this->instanceManager->getInstance(EndpointSchemaConfigurationExecuterRegistryInterface::class);
    }

    protected function getEnablingModule(): string
    {
        return EndpointFunctionalityModuleResolver::SINGLE_ENDPOINT;
    }

    protected function getSchemaConfigurationExecuterRegistry(): SchemaConfigurationExecuterRegistryInterface
    {
        return $this->getEndpointSchemaConfigurationExecuterRegistry();
    }
}
