<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaTypeModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\Blocks\MainPluginBlockTrait;

class SchemaConfigSelfFieldsBlock extends AbstractDefaultEnableDisableFunctionalitySchemaConfigBlock
{
    use MainPluginBlockTrait;

    protected function getBlockName(): string
    {
        return 'schema-config-self-fields';
    }

    public function getBlockPriority(): int
    {
        return 138;
    }

    public function getEnablingModule(): ?string
    {
        return SchemaTypeModuleResolver::SCHEMA_SELF_FIELDS;
    }

    protected function getBlockLabel(): string
    {
        return \__('Add self fields to schema?', 'graphql-api');
    }

    protected function getBlockTitle(): string
    {
        return \__('Schema Self Fields', 'graphql-api');
    }
}
