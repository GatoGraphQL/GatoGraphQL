<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\Blocks\MainPluginBlockTrait;

class SchemaConfigNamespacingBlock extends AbstractDefaultEnableDisableFunctionalitySchemaConfigBlock
{
    use MainPluginBlockTrait;

    protected function getBlockName(): string
    {
        return 'schema-config-namespacing';
    }

    public function getBlockPriority(): int
    {
        return 110;
    }

    public function getEnablingModule(): ?string
    {
        return SchemaConfigurationFunctionalityModuleResolver::SCHEMA_NAMESPACING;
    }

    protected function getBlockLabel(): string
    {
        return \__('Use namespacing?', 'graphql-api');
    }

    protected function getBlockTitle(): string
    {
        return \__('Namespacing', 'graphql-api');
    }
}
