<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\SchemaConfigurationExecuters;

use GatoGraphQL\GatoGraphQL\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;
use GatoGraphQL\GatoGraphQL\Services\Blocks\BlockInterface;
use GatoGraphQL\GatoGraphQL\Services\Blocks\SchemaConfigExposeSensitiveDataBlock;
use PoP\ComponentModel\Module as ComponentModelModule;
use PoP\ComponentModel\Environment as ComponentModelEnvironment;

class ExposeSensitiveDataBlockSchemaConfigurationExecuter extends AbstractDefaultEnableDisableFunctionalityBlockSchemaConfigurationExecuter implements PersistedQueryEndpointSchemaConfigurationExecuterServiceTagInterface, EndpointSchemaConfigurationExecuterServiceTagInterface
{
    private ?SchemaConfigExposeSensitiveDataBlock $schemaConfigExposeSensitiveDataBlock = null;

    final protected function getSchemaConfigExposeSensitiveDataBlock(): SchemaConfigExposeSensitiveDataBlock
    {
        if ($this->schemaConfigExposeSensitiveDataBlock === null) {
            /** @var SchemaConfigExposeSensitiveDataBlock */
            $schemaConfigExposeSensitiveDataBlock = $this->instanceManager->getInstance(SchemaConfigExposeSensitiveDataBlock::class);
            $this->schemaConfigExposeSensitiveDataBlock = $schemaConfigExposeSensitiveDataBlock;
        }
        return $this->schemaConfigExposeSensitiveDataBlock;
    }

    public function getEnablingModule(): ?string
    {
        return SchemaConfigurationFunctionalityModuleResolver::SCHEMA_EXPOSE_SENSITIVE_DATA;
    }

    protected function getBlock(): BlockInterface
    {
        return $this->getSchemaConfigExposeSensitiveDataBlock();
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
