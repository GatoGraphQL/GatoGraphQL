<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks\PRO;

use GraphQLAPI\GraphQLAPIPRO\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;

class SchemaConfigFieldToInputBlock extends AbstractSchemaConfigPlaceholderPROBlock
{
    use PROPluginBlockTrait;

    protected function getBlockName(): string
    {
        return 'schema-config-field-to-input';
    }

    public function getBlockPriority(): int
    {
        return 2400;
    }

    public function getEnablingModule(): ?string
    {
        return SchemaConfigurationFunctionalityModuleResolver::FIELD_TO_INPUT;
    }

    protected function getBlockLabel(): string
    {
        return \__('Enable field to input?', 'graphql-api-pro');
    }

    protected function getBlockTitle(): string
    {
        return \__('Field to Input', 'graphql-api-pro');
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
