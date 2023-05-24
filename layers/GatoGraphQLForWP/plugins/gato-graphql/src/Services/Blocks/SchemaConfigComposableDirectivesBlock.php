<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\Blocks;

use GatoGraphQL\GatoGraphQL\Services\Blocks\AbstractDefaultEnableDisableFunctionalitySchemaConfigBlock;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;

class SchemaConfigComposableDirectivesBlock extends AbstractDefaultEnableDisableFunctionalitySchemaConfigBlock
{
    use MainPluginBlockTrait;

    protected function getBlockName(): string
    {
        return 'schema-config-composable-directives';
    }

    public function getBlockPriority(): int
    {
        return 10050;
    }

    public function getEnablingModule(): ?string
    {
        return SchemaConfigurationFunctionalityModuleResolver::COMPOSABLE_DIRECTIVES;
    }

    protected function getBlockLabel(): string
    {
        return \__('Enable composable directives?', 'gato-graphql');
    }

    protected function getBlockTitle(): string
    {
        return \__('Composable Directives', 'gato-graphql');
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
