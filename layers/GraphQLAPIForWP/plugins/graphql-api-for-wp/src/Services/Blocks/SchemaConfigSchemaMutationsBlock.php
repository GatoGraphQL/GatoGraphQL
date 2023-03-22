<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks;

use GraphQLAPI\GraphQLAPI\Services\Blocks\AbstractDefaultEnableDisableFunctionalitySchemaConfigBlock;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\MutationSchemaTypeModuleResolver;

class SchemaConfigSchemaMutationsBlock extends AbstractDefaultEnableDisableFunctionalitySchemaConfigBlock
{
    use MainPluginBlockTrait;

    protected function getBlockName(): string
    {
        return 'schema-config-schema-mutations';
    }

    public function getBlockPriority(): int
    {
        return 10095;
    }

    public function getEnablingModule(): ?string
    {
        return MutationSchemaTypeModuleResolver::SCHEMA_MUTATIONS;
    }

    protected function getBlockLabel(): string
    {
        return \__('Use “payload” types for mutations in the schema?', 'graphql-api');
    }

    protected function getBlockTitle(): string
    {
        return \__('Mutations', 'graphql-api');
    }

    /**
     * Add the locale language to the localized data?
     */
    protected function addLocalLanguage(): bool
    {
        return true;
    }

    /**
     * Default language for the script/component's documentation
     */
    protected function getDefaultLanguage(): ?string
    {
        // English
        return 'en';
    }

    /**
     * Register style-index.css
     */
    protected function registerCommonStyleCSS(): bool
    {
        return true;
    }
}
