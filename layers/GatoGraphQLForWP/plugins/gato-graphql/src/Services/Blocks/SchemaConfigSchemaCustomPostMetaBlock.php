<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\Blocks;

use GatoGraphQL\GatoGraphQL\ModuleResolvers\MetaSchemaTypeModuleResolver;

class SchemaConfigSchemaCustomPostMetaBlock extends AbstractSchemaConfigSchemaMetaBlock
{
    use MainPluginBlockTrait;
    use OptionsBlockTrait;

    protected function getBlockName(): string
    {
        return 'schema-config-schema-custompost-meta';
    }

    public function getBlockPriority(): int
    {
        return 9060;
    }

    public function getEnablingModule(): ?string
    {
        return MetaSchemaTypeModuleResolver::SCHEMA_CUSTOMPOST_META;
    }

    protected function getBlockTitle(): string
    {
        return \__('Custom Post Meta', 'gato-graphql');
    }

    /**
     * Register style-index.css
     */
    protected function registerCommonStyleCSS(): bool
    {
        return true;
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
}
