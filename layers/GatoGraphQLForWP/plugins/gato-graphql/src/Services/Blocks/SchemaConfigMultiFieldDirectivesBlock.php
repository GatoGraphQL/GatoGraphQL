<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\Blocks;

use GatoGraphQL\GatoGraphQL\Services\Blocks\AbstractDefaultEnableDisableFunctionalitySchemaConfigBlock;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;

class SchemaConfigMultiFieldDirectivesBlock extends AbstractDefaultEnableDisableFunctionalitySchemaConfigBlock
{
    use MainPluginBlockTrait;

    protected function getBlockName(): string
    {
        return 'schema-config-multifield-directives';
    }

    public function getBlockPriority(): int
    {
        return 10040;
    }

    public function getEnablingModule(): ?string
    {
        return SchemaConfigurationFunctionalityModuleResolver::MULTIFIELD_DIRECTIVES;
    }

    protected function getBlockLabel(): string
    {
        return \__('Enable multi-field directives?', 'gato-graphql');
    }

    protected function getBlockTitle(): string
    {
        return \__('Multi-Field Directives', 'gato-graphql');
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
