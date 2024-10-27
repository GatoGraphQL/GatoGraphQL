<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\SchemaConfigurationExecuters;

use GatoGraphQL\GatoGraphQL\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;
use GatoGraphQL\GatoGraphQL\Services\Blocks\BlockInterface;
use GatoGraphQL\GatoGraphQL\Services\Blocks\SchemaConfigNamespacingBlock;
use PoP\ComponentModel\Module as ComponentModelModule;
use PoP\ComponentModel\Environment as ComponentModelEnvironment;

class NamespacingBlockSchemaConfigurationExecuter extends AbstractDefaultEnableDisableFunctionalityBlockSchemaConfigurationExecuter implements PersistedQueryEndpointSchemaConfigurationExecuterServiceTagInterface, EndpointSchemaConfigurationExecuterServiceTagInterface
{
    private ?SchemaConfigNamespacingBlock $schemaConfigNamespacingBlock = null;

    final protected function getSchemaConfigNamespacingBlock(): SchemaConfigNamespacingBlock
    {
        if ($this->schemaConfigNamespacingBlock === null) {
            /** @var SchemaConfigNamespacingBlock */
            $schemaConfigNamespacingBlock = $this->instanceManager->getInstance(SchemaConfigNamespacingBlock::class);
            $this->schemaConfigNamespacingBlock = $schemaConfigNamespacingBlock;
        }
        return $this->schemaConfigNamespacingBlock;
    }

    public function getEnablingModule(): ?string
    {
        return SchemaConfigurationFunctionalityModuleResolver::SCHEMA_NAMESPACING;
    }

    protected function getBlock(): BlockInterface
    {
        return $this->getSchemaConfigNamespacingBlock();
    }

    public function getHookModuleClass(): string
    {
        return ComponentModelModule::class;
    }

    public function getHookEnvironmentClass(): string
    {
        return ComponentModelEnvironment::NAMESPACE_TYPES_AND_INTERFACES;
    }
}
