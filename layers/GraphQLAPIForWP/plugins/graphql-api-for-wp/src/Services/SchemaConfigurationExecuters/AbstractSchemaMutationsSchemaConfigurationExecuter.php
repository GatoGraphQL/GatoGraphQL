<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters;

use GraphQLAPI\GraphQLAPI\Services\Blocks\BlockInterface;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters\AbstractDefaultEnableDisableFunctionalitySchemaConfigurationExecuter;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters\EndpointSchemaConfigurationExecuterServiceTagInterface;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters\PersistedQueryEndpointSchemaConfigurationExecuterServiceTagInterface;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\MutationSchemaTypeModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigPayloadTypesInMutationsBlock;

abstract class AbstractSchemaMutationsSchemaConfigurationExecuter extends AbstractDefaultEnableDisableFunctionalitySchemaConfigurationExecuter implements PersistedQueryEndpointSchemaConfigurationExecuterServiceTagInterface, EndpointSchemaConfigurationExecuterServiceTagInterface
{
    private ?SchemaConfigPayloadTypesInMutationsBlock $schemaConfigPayloadTypesInMutationsBlock = null;

    final public function setSchemaConfigPayloadTypesInMutationsBlock(SchemaConfigPayloadTypesInMutationsBlock $schemaConfigPayloadTypesInMutationsBlock): void
    {
        $this->schemaConfigPayloadTypesInMutationsBlock = $schemaConfigPayloadTypesInMutationsBlock;
    }
    final protected function getSchemaConfigPayloadTypesInMutationsBlock(): SchemaConfigPayloadTypesInMutationsBlock
    {
        /** @var SchemaConfigPayloadTypesInMutationsBlock */
        return $this->schemaConfigPayloadTypesInMutationsBlock ??= $this->instanceManager->getInstance(SchemaConfigPayloadTypesInMutationsBlock::class);
    }

    public function getEnablingModule(): ?string
    {
        return MutationSchemaTypeModuleResolver::SCHEMA_MUTATIONS;
    }

    protected function getBlock(): BlockInterface
    {
        return $this->getSchemaConfigPayloadTypesInMutationsBlock();
    }
}
