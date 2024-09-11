<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\SchemaConfigurationExecuters;

use GatoGraphQL\GatoGraphQL\Services\Blocks\BlockInterface;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;
use GatoGraphQL\GatoGraphQL\Services\Blocks\SchemaConfigSelfFieldsBlock;
use PoP\ComponentModel\Module as ComponentModelModule;
use PoP\ComponentModel\Environment as ComponentModelEnvironment;

class SelfFieldsBlockSchemaConfigurationExecuter extends AbstractDefaultEnableDisableFunctionalityBlockSchemaConfigurationExecuter implements PersistedQueryEndpointSchemaConfigurationExecuterServiceTagInterface, EndpointSchemaConfigurationExecuterServiceTagInterface
{
    private ?SchemaConfigSelfFieldsBlock $schemaConfigSelfFieldsBlock = null;

    final public function setSchemaConfigSelfFieldsBlock(SchemaConfigSelfFieldsBlock $schemaConfigSelfFieldsBlock): void
    {
        $this->schemaConfigSelfFieldsBlock = $schemaConfigSelfFieldsBlock;
    }
    final protected function getSchemaConfigSelfFieldsBlock(): SchemaConfigSelfFieldsBlock
    {
        if ($this->schemaConfigSelfFieldsBlock === null) {
            /** @var SchemaConfigSelfFieldsBlock */
            $schemaConfigSelfFieldsBlock = $this->instanceManager->getInstance(SchemaConfigSelfFieldsBlock::class);
            $this->schemaConfigSelfFieldsBlock = $schemaConfigSelfFieldsBlock;
        }
        return $this->schemaConfigSelfFieldsBlock;
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
