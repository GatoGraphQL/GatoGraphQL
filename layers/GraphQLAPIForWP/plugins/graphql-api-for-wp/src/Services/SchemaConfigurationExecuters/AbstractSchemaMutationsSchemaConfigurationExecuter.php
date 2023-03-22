<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters;

use GraphQLAPI\GraphQLAPI\Services\Blocks\BlockInterface;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters\AbstractDefaultEnableDisableFunctionalitySchemaConfigurationExecuter;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters\EndpointSchemaConfigurationExecuterServiceTagInterface;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters\PersistedQueryEndpointSchemaConfigurationExecuterServiceTagInterface;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\MutationSchemaTypeModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigSchemaMutationsBlock;

abstract class AbstractSchemaMutationsSchemaConfigurationExecuter extends AbstractDefaultEnableDisableFunctionalitySchemaConfigurationExecuter implements PersistedQueryEndpointSchemaConfigurationExecuterServiceTagInterface, EndpointSchemaConfigurationExecuterServiceTagInterface
{
    private ?SchemaConfigSchemaMutationsBlock $schemaConfigSchemaMutationsBlock = null;

    final public function setSchemaConfigSchemaMutationsBlock(SchemaConfigSchemaMutationsBlock $schemaConfigSchemaMutationsBlock): void
    {
        $this->schemaConfigSchemaMutationsBlock = $schemaConfigSchemaMutationsBlock;
    }
    final protected function getSchemaConfigSchemaMutationsBlock(): SchemaConfigSchemaMutationsBlock
    {
        /** @var SchemaConfigSchemaMutationsBlock */
        return $this->schemaConfigSchemaMutationsBlock ??= $this->instanceManager->getInstance(SchemaConfigSchemaMutationsBlock::class);
    }

    public function getEnablingModule(): ?string
    {
        return MutationSchemaTypeModuleResolver::SCHEMA_MUTATIONS;
    }

    protected function getBlock(): BlockInterface
    {
        return $this->getSchemaConfigSchemaMutationsBlock();
    }
}
