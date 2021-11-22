<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaTypeModuleResolver;

class SchemaConfigExposeAdminDataBlock extends AbstractDefaultEnableDisableFunctionalitySchemaConfigBlock
{
    use MainPluginBlockTrait;

    protected function getBlockName(): string
    {
        return 'schema-config-expose-admin-data';
    }

    public function getBlockPriority(): int
    {
        return 140;
    }

    public function getEnablingModule(): ?string
    {
        return SchemaTypeModuleResolver::SCHEMA_EXPOSE_ADMIN_DATA;
    }

    protected function getBlockLabel(): string
    {
        return \__('Add admin fields to schema?', 'graphql-api');
    }

    protected function getBlockTitle(): string
    {
        return \__('Schema Expose Admin Data', 'graphql-api');
    }
}
