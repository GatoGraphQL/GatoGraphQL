<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\Blocks;

use GatoGraphQL\GatoGraphQL\ModuleResolvers\MetaSchemaTypeModuleResolver;

class SchemaConfigSchemaTaxonomyMetaBlock extends AbstractSchemaConfigSchemaMetaBlock
{
    use MainPluginBlockTrait;
    use OptionsBlockTrait;

    protected function getBlockName(): string
    {
        return 'schema-config-schema-taxonomy-meta';
    }

    public function getBlockPriority(): int
    {
        return 9030;
    }

    public function getEnablingModule(): ?string
    {
        return MetaSchemaTypeModuleResolver::SCHEMA_TAXONOMY_META;
    }

    protected function getBlockTitle(): string
    {
        return \__('Taxonomy Meta', 'gato-graphql');
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
