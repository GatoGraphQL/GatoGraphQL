<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaTypeModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigSelfFieldsBlock;
use GraphQLByPoP\GraphQLServer\ComponentConfiguration as GraphQLServerComponentConfiguration;
use GraphQLByPoP\GraphQLServer\Environment as GraphQLServerEnvironment;

class SelfFieldsSchemaConfigurationExecuter extends AbstractDefaultEnableDisableFunctionalitySchemaConfigurationExecuter implements PersistedQueryEndpointSchemaConfigurationExecuterServiceTagInterface, EndpointSchemaConfigurationExecuterServiceTagInterface
{
    public function getEnablingModule(): ?string
    {
        return SchemaTypeModuleResolver::SCHEMA_SELF_FIELDS;
    }

    protected function getBlockClass(): string
    {
        return SchemaConfigSelfFieldsBlock::class;
    }

    public function getHookComponentConfigurationClass(): string
    {
        return GraphQLServerComponentConfiguration::class;
    }

    public function getHookEnvironmentClass(): string
    {
        return GraphQLServerEnvironment::ADD_SELF_FIELD_TO_SCHEMA;
    }
}
