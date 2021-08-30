<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators;

use GraphQLAPI\GraphQLAPI\Registries\EndpointSchemaConfigurationExecuterRegistryInterface;
use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLAPI\GraphQLAPI\Registries\SchemaConfigurationExecuterRegistryInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;

class SingleEndpointSchemaConfigurator extends AbstractSchemaConfigurationEndpointSchemaConfigurator
{
    public function __construct(
        InstanceManagerInterface $instanceManager,
        ModuleRegistryInterface $moduleRegistry,
        protected EndpointSchemaConfigurationExecuterRegistryInterface $endpointSchemaConfigurationExecuterRegistry
    ) {
        parent::__construct(
            $instanceManager,
            $moduleRegistry,
        );
    }

    protected function getSchemaConfigurationExecuterRegistry(): SchemaConfigurationExecuterRegistryInterface
    {
        return $this->endpointSchemaConfigurationExecuterRegistry;
    }
}
