<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters;

use GraphQLAPI\GraphQLAPI\Services\Blocks\BlockInterface;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters\AbstractDefaultEnableDisableFunctionalitySchemaConfigurationExecuter;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters\EndpointSchemaConfigurationExecuterServiceTagInterface;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters\PersistedQueryEndpointSchemaConfigurationExecuterServiceTagInterface;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigSelfFieldsBlock;
use PoP\ComponentModel\Module as ComponentModelModule;
use PoP\ComponentModel\Environment as ComponentModelEnvironment;

class SelfFieldsSchemaConfigurationExecuter extends AbstractDefaultEnableDisableFunctionalitySchemaConfigurationExecuter implements PersistedQueryEndpointSchemaConfigurationExecuterServiceTagInterface, EndpointSchemaConfigurationExecuterServiceTagInterface
{
    private ?SchemaConfigSelfFieldsBlock $schemaConfigSelfFieldsBlock = null;

    final public function setSchemaConfigSelfFieldsBlock(SchemaConfigSelfFieldsBlock $schemaConfigSelfFieldsBlock): void
    {
        $this->schemaConfigSelfFieldsBlock = $schemaConfigSelfFieldsBlock;
    }
    final protected function getSchemaConfigSelfFieldsBlock(): SchemaConfigSelfFieldsBlock
    {
        /** @var SchemaConfigSelfFieldsBlock */
        return $this->schemaConfigSelfFieldsBlock ??= $this->instanceManager->getInstance(SchemaConfigSelfFieldsBlock::class);
    }

    public function getEnablingModule(): ?string
    {
        return SchemaConfigurationFunctionalityModuleResolver::SCHEMA_SELF_FIELDS;
    }

    protected function getBlock(): BlockInterface
    {
        return $this->getSchemaConfigSelfFieldsBlock();
    }

    public function getHookModuleClass(): string
    {
        return ComponentModelModule::class;
    }

    public function getHookEnvironmentClass(): string
    {
        return ComponentModelEnvironment::ENABLE_SELF_FIELD;
    }
}
