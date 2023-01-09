<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks;

use GraphQLAPI\GraphQLAPI\Services\Blocks\AbstractDefaultEnableDisableFunctionalitySchemaConfigBlock;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;

class SchemaConfigSelfFieldsBlock extends AbstractDefaultEnableDisableFunctionalitySchemaConfigBlock
{
    use MainPluginBlockTrait;
    
    protected function getBlockName(): string
    {
        return 'schema-config-self-fields';
    }

    public function getBlockPriority(): int
    {
        return 10100;
    }

    public function getEnablingModule(): ?string
    {
        return SchemaConfigurationFunctionalityModuleResolver::SCHEMA_SELF_FIELDS;
    }

    protected function getBlockLabel(): string
    {
        return \__('Expose self fields in the schema?', 'graphql-api');
    }

    protected function getBlockTitle(): string
    {
        return \__('Self Fields', 'graphql-api');
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
