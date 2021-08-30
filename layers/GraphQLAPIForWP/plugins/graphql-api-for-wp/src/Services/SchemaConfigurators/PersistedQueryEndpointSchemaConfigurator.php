<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\EndpointFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLAPI\GraphQLAPI\Registries\PersistedQueryEndpointSchemaConfigurationExecuterRegistryInterface;
use GraphQLAPI\GraphQLAPI\Registries\SchemaConfigurationExecuterRegistryInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;

class PersistedQueryEndpointSchemaConfigurator extends AbstractCustomPostEndpointSchemaConfigurator
{
    public function __construct(
        InstanceManagerInterface $instanceManager,
        ModuleRegistryInterface $moduleRegistry,
        protected PersistedQueryEndpointSchemaConfigurationExecuterRegistryInterface $persistedQueryEndpointSchemaConfigurationExecuterRegistry
    ) {
        parent::__construct(
            $instanceManager,
            $moduleRegistry,
        );
    }

    /**
     * Only enable the service, if the corresponding module is also enabled
     */
    public function isServiceEnabled(): bool
    {
        return $this->moduleRegistry->isModuleEnabled(EndpointFunctionalityModuleResolver::PERSISTED_QUERIES)
            && parent::isServiceEnabled();
    }

    protected function getSchemaConfigurationExecuterRegistry(): SchemaConfigurationExecuterRegistryInterface
    {
        return $this->persistedQueryEndpointSchemaConfigurationExecuterRegistry;
    }
}
