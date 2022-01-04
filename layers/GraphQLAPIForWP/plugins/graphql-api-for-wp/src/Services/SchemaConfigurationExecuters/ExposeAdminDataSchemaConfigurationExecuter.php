<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaTypeModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\Blocks\BlockInterface;
use GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigExposeAdminDataBlock;
use PoP\ComponentModel\Component as ComponentModelComponent;
use PoP\ComponentModel\Environment as ComponentModelEnvironment;

class ExposeAdminDataSchemaConfigurationExecuter extends AbstractDefaultEnableDisableFunctionalitySchemaConfigurationExecuter implements PersistedQueryEndpointSchemaConfigurationExecuterServiceTagInterface, EndpointSchemaConfigurationExecuterServiceTagInterface
{
    private ?SchemaConfigExposeAdminDataBlock $schemaConfigExposeAdminDataBlock = null;

    final public function setSchemaConfigExposeAdminDataBlock(SchemaConfigExposeAdminDataBlock $schemaConfigExposeAdminDataBlock): void
    {
        $this->schemaConfigExposeAdminDataBlock = $schemaConfigExposeAdminDataBlock;
    }
    final protected function getSchemaConfigExposeAdminDataBlock(): SchemaConfigExposeAdminDataBlock
    {
        return $this->schemaConfigExposeAdminDataBlock ??= $this->instanceManager->getInstance(SchemaConfigExposeAdminDataBlock::class);
    }

    public function getEnablingModule(): ?string
    {
        return SchemaTypeModuleResolver::SCHEMA_EXPOSE_ADMIN_DATA;
    }

    protected function getBlock(): BlockInterface
    {
        return $this->getSchemaConfigExposeAdminDataBlock();
    }

    public function getHookComponentClass(): string
    {
        return ComponentModelComponent::class;
    }

    public function getHookEnvironmentClass(): string
    {
        return ComponentModelEnvironment::ENABLE_ADMIN_SCHEMA;
    }
}
