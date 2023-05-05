<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\Blocks;

use GatoGraphQL\GatoGraphQL\ModuleResolvers\SchemaTypeModuleResolver;

class SchemaConfigSchemaSettingsBlock extends AbstractSchemaConfigSchemaAllowAccessToEntriesBlock
{
    use MainPluginBlockTrait;
    use OptionsBlockTrait;

    protected function getBlockName(): string
    {
        return 'schema-config-schema-settings';
    }

    public function getBlockPriority(): int
    {
        return 9065;
    }

    public function getEnablingModule(): ?string
    {
        return SchemaTypeModuleResolver::SCHEMA_SETTINGS;
    }

    protected function getBlockTitle(): string
    {
        return \__('Settings', 'gato-graphql');
    }

    protected function getRenderBlockLabel(): string
    {
        return $this->__('Settings entries', 'gato-graphql');
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
