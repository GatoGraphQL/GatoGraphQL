<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\Blocks\BlockInterface;
use GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigExposeAdminDataBlock;
use PoP\ComponentModel\Module as ComponentModelModule;
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
        /** @var SchemaConfigExposeAdminDataBlock */
        return $this->schemaConfigExposeAdminDataBlock ??= $this->instanceManager->getInstance(SchemaConfigExposeAdminDataBlock::class);
    }

    public function getEnablingModule(): ?string
    {
        return SchemaConfigurationFunctionalityModuleResolver::SCHEMA_EXPOSE_SENSITIVE_DATA;
    }

    protected function getBlock(): BlockInterface
    {
        return $this->getSchemaConfigExposeAdminDataBlock();
    }

    public function getHookModuleClass(): string
    {
        return ComponentModelModule::class;
    }

    public function getHookEnvironmentClass(): string
    {
        return ComponentModelEnvironment::EXPOSE_SENSITIVE_DATA_IN_SCHEMA;
    }
}
