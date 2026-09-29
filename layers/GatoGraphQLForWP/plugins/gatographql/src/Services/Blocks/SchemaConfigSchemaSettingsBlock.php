<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\Blocks;

use GatoGraphQL\GatoGraphQL\ModuleResolvers\SchemaTypeModuleResolver;
use PoPSchema\SchemaCommons\Constants\Behaviors;

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
        return \__('Settings', 'gatographql');
    }

    protected function getRenderBlockLabel(): string
    {
        return $this->__('Settings entries', 'gatographql');
    }

    /**
     * Same default as the "Settings" module: an allowlist, so the options
     * are not disclosed by a Schema Configuration that leaves the
     * behavior unset.
     */
    protected function getDefaultBehavior(): string
    {
        return Behaviors::ALLOW;
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
