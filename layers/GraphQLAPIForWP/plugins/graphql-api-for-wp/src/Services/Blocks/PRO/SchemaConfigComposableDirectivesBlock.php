<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks\PRO;

use GraphQLAPI\GraphQLAPI\Services\Blocks\AbstractDefaultEnableDisableFunctionalitySchemaConfigBlock;
use GraphQLAPI\GraphQLAPIPRO\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;

class SchemaConfigComposableDirectivesBlock extends AbstractSchemaConfigPlaceholderPROBlock
{
    use PROPluginBlockTrait;

    protected function getBlockName(): string
    {
        return 'schema-config-composable-directives';
    }

    public function getBlockPriority(): int
    {
        return 2200;
    }

    public function getEnablingModule(): ?string
    {
        return SchemaConfigurationFunctionalityModuleResolver::COMPOSABLE_DIRECTIVES;
    }

    protected function getBlockLabel(): string
    {
        return \__('Enable composable directives?', 'graphql-api-pro');
    }

    protected function getBlockTitle(): string
    {
        return \__('Composable Directives', 'graphql-api-pro');
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
