<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\EndpointFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Registries\EndpointSchemaConfigurationExecuterRegistryInterface;
use GraphQLAPI\GraphQLAPI\Registries\SchemaConfigurationExecuterRegistryInterface;
use Symfony\Contracts\Service\Attribute\Required;

class CustomEndpointSchemaConfigurator extends AbstractCustomPostEndpointSchemaConfigurator
{
    private ?EndpointSchemaConfigurationExecuterRegistryInterface $endpointSchemaConfigurationExecuterRegistry = null;

    public function setEndpointSchemaConfigurationExecuterRegistry(EndpointSchemaConfigurationExecuterRegistryInterface $endpointSchemaConfigurationExecuterRegistry): void
    {
        $this->endpointSchemaConfigurationExecuterRegistry = $endpointSchemaConfigurationExecuterRegistry;
    }
    protected function getEndpointSchemaConfigurationExecuterRegistry(): EndpointSchemaConfigurationExecuterRegistryInterface
    {
        return $this->endpointSchemaConfigurationExecuterRegistry ??= $this->instanceManager->getInstance(EndpointSchemaConfigurationExecuterRegistryInterface::class);
    }

    /**
     * Only enable the service, if the corresponding module is also enabled
     */
    public function isServiceEnabled(): bool
    {
        return $this->getModuleRegistry()->isModuleEnabled(EndpointFunctionalityModuleResolver::CUSTOM_ENDPOINTS)
            && parent::isServiceEnabled();
    }

    protected function getSchemaConfigurationExecuterRegistry(): SchemaConfigurationExecuterRegistryInterface
    {
        return $this->getEndpointSchemaConfigurationExecuterRegistry();
    }
}
