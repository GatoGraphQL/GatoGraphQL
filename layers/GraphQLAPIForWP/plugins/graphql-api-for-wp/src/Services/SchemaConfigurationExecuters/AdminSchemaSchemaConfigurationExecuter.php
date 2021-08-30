<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaTypeModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigAdminFieldsBlock;
use PoP\ComponentModel\ComponentConfiguration as ComponentModelComponentConfiguration;
use PoP\ComponentModel\Environment as ComponentModelEnvironment;

class AdminSchemaSchemaConfigurationExecuter extends AbstractDefaultEnableDisableFunctionalitySchemaConfigurationExecuter implements PersistedQueryEndpointSchemaConfigurationExecuterServiceTagInterface, EndpointSchemaConfigurationExecuterServiceTagInterface
{
    public function getEnablingModule(): ?string
    {
        return SchemaTypeModuleResolver::SCHEMA_ADMIN_FIELDS;
    }

    protected function getBlockClass(): string
    {
        return SchemaConfigAdminFieldsBlock::class;
    }

    public function getHookComponentConfigurationClass(): string
    {
        return ComponentModelComponentConfiguration::class;
    }

    public function getHookEnvironmentClass(): string
    {
        return ComponentModelEnvironment::ENABLE_ADMIN_SCHEMA;
    }
}
