<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters;

use GraphQLAPI\GraphQLAPI\Services\Blocks\BlockInterface;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters\AbstractDefaultEnableDisableFunctionalityBlockSchemaConfigurationExecuter;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters\EndpointSchemaConfigurationExecuterServiceTagInterface;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters\PersistedQueryEndpointSchemaConfigurationExecuterServiceTagInterface;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigPayloadTypesForMutationsBlock;

abstract class AbstractSchemaMutationsBlockSchemaConfigurationExecuter extends AbstractDefaultEnableDisableFunctionalityBlockSchemaConfigurationExecuter implements PersistedQueryEndpointSchemaConfigurationExecuterServiceTagInterface, EndpointSchemaConfigurationExecuterServiceTagInterface
{
    private ?SchemaConfigPayloadTypesForMutationsBlock $schemaConfigPayloadTypesForMutationsBlock = null;

    final public function setSchemaConfigPayloadTypesForMutationsBlock(SchemaConfigPayloadTypesForMutationsBlock $schemaConfigPayloadTypesForMutationsBlock): void
    {
        $this->schemaConfigPayloadTypesForMutationsBlock = $schemaConfigPayloadTypesForMutationsBlock;
    }
    final protected function getSchemaConfigPayloadTypesForMutationsBlock(): SchemaConfigPayloadTypesForMutationsBlock
    {
        /** @var SchemaConfigPayloadTypesForMutationsBlock */
        return $this->schemaConfigPayloadTypesForMutationsBlock ??= $this->instanceManager->getInstance(SchemaConfigPayloadTypesForMutationsBlock::class);
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
