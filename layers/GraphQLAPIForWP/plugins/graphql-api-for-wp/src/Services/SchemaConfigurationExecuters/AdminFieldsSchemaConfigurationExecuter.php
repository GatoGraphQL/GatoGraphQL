<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaTypeModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\Blocks\BlockInterface;
use GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigExposeAdminDataBlock;
use PoP\ComponentModel\ComponentConfiguration as ComponentModelComponentConfiguration;
use PoP\ComponentModel\Environment as ComponentModelEnvironment;

class AdminFieldsSchemaConfigurationExecuter extends AbstractDefaultEnableDisableFunctionalitySchemaConfigurationExecuter implements PersistedQueryEndpointSchemaConfigurationExecuterServiceTagInterface, EndpointSchemaConfigurationExecuterServiceTagInterface
{
    private ?SchemaConfigExposeAdminDataBlock $schemaConfigAdminFieldsBlock = null;

    final public function setSchemaConfigExposeAdminDataBlock(SchemaConfigExposeAdminDataBlock $schemaConfigAdminFieldsBlock): void
    {
        $this->schemaConfigAdminFieldsBlock = $schemaConfigAdminFieldsBlock;
    }
    final protected function getSchemaConfigExposeAdminDataBlock(): SchemaConfigExposeAdminDataBlock
    {
        return $this->schemaConfigAdminFieldsBlock ??= $this->instanceManager->getInstance(SchemaConfigExposeAdminDataBlock::class);
    }

    public function getEnablingModule(): ?string
    {
        return SchemaTypeModuleResolver::SCHEMA_EXPOSE_ADMIN_DATA;
    }

    protected function getBlock(): BlockInterface
    {
        return $this->getSchemaConfigExposeAdminDataBlock();
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
