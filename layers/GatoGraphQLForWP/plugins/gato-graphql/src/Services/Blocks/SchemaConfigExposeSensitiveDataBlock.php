<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\Blocks;

use GatoGraphQL\GatoGraphQL\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;

class SchemaConfigExposeSensitiveDataBlock extends AbstractDefaultEnableDisableFunctionalitySchemaConfigBlock
{
    use MainPluginBlockTrait;

    protected function getBlockName(): string
    {
        return 'schema-config-expose-sensitive-data';
    }

    public function getBlockPriority(): int
    {
        return 10110;
    }

    public function getEnablingModule(): ?string
    {
        return SchemaConfigurationFunctionalityModuleResolver::SCHEMA_EXPOSE_SENSITIVE_DATA;
    }

    protected function getBlockLabel(): string
    {
        return \__('Add “sensitive” fields to the schema?', 'gato-graphql');
    }

    protected function getBlockTitle(): string
    {
        return \__('Expose Sensitive Data in the Schema', 'gato-graphql');
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
