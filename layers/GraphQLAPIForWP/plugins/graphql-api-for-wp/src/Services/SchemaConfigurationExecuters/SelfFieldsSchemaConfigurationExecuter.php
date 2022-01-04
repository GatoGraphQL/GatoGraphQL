<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaTypeModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\Blocks\BlockInterface;
use GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigSelfFieldsBlock;
use GraphQLByPoP\GraphQLServer\Component as GraphQLServerComponent;
use GraphQLByPoP\GraphQLServer\ComponentConfiguration as GraphQLServerComponentConfiguration;
use GraphQLByPoP\GraphQLServer\Environment as GraphQLServerEnvironment;

class SelfFieldsSchemaConfigurationExecuter extends AbstractDefaultEnableDisableFunctionalitySchemaConfigurationExecuter implements PersistedQueryEndpointSchemaConfigurationExecuterServiceTagInterface, EndpointSchemaConfigurationExecuterServiceTagInterface
{
    private ?SchemaConfigSelfFieldsBlock $schemaConfigSelfFieldsBlock = null;

    final public function setSchemaConfigSelfFieldsBlock(SchemaConfigSelfFieldsBlock $schemaConfigSelfFieldsBlock): void
    {
        $this->schemaConfigSelfFieldsBlock = $schemaConfigSelfFieldsBlock;
    }
    final protected function getSchemaConfigSelfFieldsBlock(): SchemaConfigSelfFieldsBlock
    {
        return $this->schemaConfigSelfFieldsBlock ??= $this->instanceManager->getInstance(SchemaConfigSelfFieldsBlock::class);
    }

    public function getEnablingModule(): ?string
    {
        return SchemaTypeModuleResolver::SCHEMA_SELF_FIELDS;
    }

    protected function getBlock(): BlockInterface
    {
        return $this->getSchemaConfigSelfFieldsBlock();
    }

    public function getHookComponentConfigurationClass(): string
    {
        return GraphQLServerComponentConfiguration::class;
    }

    public function getHookEnvironmentClass(): string
    {
        return GraphQLServerEnvironment::EXPOSE_SELF_FIELD_IN_GRAPHQL_SCHEMA;
    }
}
