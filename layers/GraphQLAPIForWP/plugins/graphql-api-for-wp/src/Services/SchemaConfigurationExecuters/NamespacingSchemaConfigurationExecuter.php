<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\Blocks\BlockInterface;
use GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigNamespacingBlock;
use PoP\ComponentModel\ComponentConfiguration as ComponentModelComponentConfiguration;
use PoP\ComponentModel\Environment as ComponentModelEnvironment;
use Symfony\Contracts\Service\Attribute\Required;

class NamespacingSchemaConfigurationExecuter extends AbstractDefaultEnableDisableFunctionalitySchemaConfigurationExecuter implements PersistedQueryEndpointSchemaConfigurationExecuterServiceTagInterface, EndpointSchemaConfigurationExecuterServiceTagInterface
{
    private ?SchemaConfigNamespacingBlock $schemaConfigNamespacingBlock = null;

    public function setSchemaConfigNamespacingBlock(SchemaConfigNamespacingBlock $schemaConfigNamespacingBlock): void
    {
        $this->schemaConfigNamespacingBlock = $schemaConfigNamespacingBlock;
    }
    protected function getSchemaConfigNamespacingBlock(): SchemaConfigNamespacingBlock
    {
        return $this->schemaConfigNamespacingBlock ??= $this->instanceManager->getInstance(SchemaConfigNamespacingBlock::class);
    }

    public function getEnablingModule(): ?string
    {
        return SchemaConfigurationFunctionalityModuleResolver::SCHEMA_NAMESPACING;
    }

    protected function getBlock(): BlockInterface
    {
        return $this->getSchemaConfigNamespacingBlock();
    }

    public function getHookComponentConfigurationClass(): string
    {
        return ComponentModelComponentConfiguration::class;
    }

    public function getHookEnvironmentClass(): string
    {
        return ComponentModelEnvironment::NAMESPACE_TYPES_AND_INTERFACES;
    }
}
