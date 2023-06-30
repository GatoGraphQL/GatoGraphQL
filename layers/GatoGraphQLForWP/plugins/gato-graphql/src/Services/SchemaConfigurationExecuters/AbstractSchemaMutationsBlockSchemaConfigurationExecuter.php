<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\SchemaConfigurationExecuters;

use GatoGraphQL\GatoGraphQL\Services\Blocks\BlockInterface;
use GatoGraphQL\GatoGraphQL\Services\SchemaConfigurationExecuters\AbstractDefaultEnableDisableFunctionalityBlockSchemaConfigurationExecuter;
use GatoGraphQL\GatoGraphQL\Services\SchemaConfigurationExecuters\EndpointSchemaConfigurationExecuterServiceTagInterface;
use GatoGraphQL\GatoGraphQL\Services\SchemaConfigurationExecuters\PersistedQueryEndpointSchemaConfigurationExecuterServiceTagInterface;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;
use GatoGraphQL\GatoGraphQL\Services\Blocks\SchemaConfigPayloadTypesForMutationsBlock;

abstract class AbstractSchemaMutationsBlockSchemaConfigurationExecuter extends AbstractDefaultEnableDisableFunctionalityBlockSchemaConfigurationExecuter implements PersistedQueryEndpointSchemaConfigurationExecuterServiceTagInterface, EndpointSchemaConfigurationExecuterServiceTagInterface
{
    private ?SchemaConfigPayloadTypesForMutationsBlock $schemaConfigPayloadTypesForMutationsBlock = null;

    final public function setSchemaConfigPayloadTypesForMutationsBlock(SchemaConfigPayloadTypesForMutationsBlock $schemaConfigPayloadTypesForMutationsBlock): void
    {
        $this->schemaConfigPayloadTypesForMutationsBlock = $schemaConfigPayloadTypesForMutationsBlock;
    }
    final protected function getSchemaConfigPayloadTypesForMutationsBlock(): SchemaConfigPayloadTypesForMutationsBlock
    {
        if ($this->schemaConfigPayloadTypesForMutationsBlock === null) {
            /** @var SchemaConfigPayloadTypesForMutationsBlock */
            $schemaConfigPayloadTypesForMutationsBlock = $this->instanceManager->getInstance(SchemaConfigPayloadTypesForMutationsBlock::class);
            $this->schemaConfigPayloadTypesForMutationsBlock = $schemaConfigPayloadTypesForMutationsBlock;
        }
        return $this->schemaConfigPayloadTypesForMutationsBlock;
    }

    public function getEnablingModule(): ?string
    {
        return SchemaConfigurationFunctionalityModuleResolver::MUTATIONS;
    }

    protected function getBlock(): BlockInterface
    {
        return $this->getSchemaConfigPayloadTypesForMutationsBlock();
    }
}
