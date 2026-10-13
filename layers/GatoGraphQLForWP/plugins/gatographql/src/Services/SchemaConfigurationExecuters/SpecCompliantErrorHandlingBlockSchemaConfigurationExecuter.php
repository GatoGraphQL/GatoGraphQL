<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\SchemaConfigurationExecuters;

use GatoGraphQL\GatoGraphQL\Services\Blocks\BlockInterface;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;
use GatoGraphQL\GatoGraphQL\Services\Blocks\SchemaConfigSpecCompliantErrorHandlingBlock;
use PoP\ComponentModel\Module as ComponentModelModule;
use PoP\ComponentModel\Environment as ComponentModelEnvironment;

class SpecCompliantErrorHandlingBlockSchemaConfigurationExecuter extends AbstractDefaultEnableDisableFunctionalityBlockSchemaConfigurationExecuter implements PersistedQueryEndpointSchemaConfigurationExecuterServiceTagInterface, EndpointSchemaConfigurationExecuterServiceTagInterface
{
    private ?SchemaConfigSpecCompliantErrorHandlingBlock $schemaConfigSpecCompliantErrorHandlingBlock = null;

    final protected function getSchemaConfigSpecCompliantErrorHandlingBlock(): SchemaConfigSpecCompliantErrorHandlingBlock
    {
        if ($this->schemaConfigSpecCompliantErrorHandlingBlock === null) {
            /** @var SchemaConfigSpecCompliantErrorHandlingBlock */
            $schemaConfigSpecCompliantErrorHandlingBlock = $this->instanceManager->getInstance(SchemaConfigSpecCompliantErrorHandlingBlock::class);
            $this->schemaConfigSpecCompliantErrorHandlingBlock = $schemaConfigSpecCompliantErrorHandlingBlock;
        }
        return $this->schemaConfigSpecCompliantErrorHandlingBlock;
    }

    public function getEnablingModule(): ?string
    {
        return SchemaConfigurationFunctionalityModuleResolver::SPEC_COMPLIANT_ERROR_HANDLING;
    }

    protected function getBlock(): BlockInterface
    {
        return $this->getSchemaConfigSpecCompliantErrorHandlingBlock();
    }

    public function getHookModuleClass(): string
    {
        return ComponentModelModule::class;
    }

    public function getHookEnvironmentClass(): string
    {
        return ComponentModelEnvironment::ENABLE_SPEC_COMPLIANT_ERROR_HANDLING;
    }
}
