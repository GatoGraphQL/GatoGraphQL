<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\EndpointFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Registries\EndpointSchemaConfigurationExecuterRegistryInterface;
use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLAPI\GraphQLAPI\Registries\SchemaConfigurationExecuterRegistryInterface;
use GraphQLAPI\GraphQLAPI\Services\Blocks\EndpointSchemaConfigurationBlock;
use GraphQLAPI\GraphQLAPI\Services\Helpers\BlockHelpers;
use PoP\ComponentModel\Instances\InstanceManagerInterface;

class CustomEndpointSchemaConfigurator extends AbstractCustomPostEndpointSchemaConfigurator
{
    protected EndpointSchemaConfigurationExecuterRegistryInterface $endpointSchemaConfigurationExecuterRegistry;
    public function __construct(
        EndpointSchemaConfigurationExecuterRegistryInterface $endpointSchemaConfigurationExecuterRegistry
    ) {
        $this->endpointSchemaConfigurationExecuterRegistry = $endpointSchemaConfigurationExecuterRegistry;
    }

    /**
     * Only enable the service, if the corresponding module is also enabled
     */
    public function isServiceEnabled(): bool
    {
        return $this->moduleRegistry->isModuleEnabled(EndpointFunctionalityModuleResolver::CUSTOM_ENDPOINTS)
            && parent::isServiceEnabled();
    }

    protected function getSchemaConfigurationExecuterRegistry(): SchemaConfigurationExecuterRegistryInterface
    {
        return $this->endpointSchemaConfigurationExecuterRegistry;
    }
}
