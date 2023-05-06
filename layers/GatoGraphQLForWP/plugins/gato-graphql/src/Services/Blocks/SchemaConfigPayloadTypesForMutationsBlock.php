<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\Blocks;

use GatoGraphQL\GatoGraphQL\Services\Blocks\AbstractDefaultEnableDisableFunctionalitySchemaConfigBlock;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;

class SchemaConfigPayloadTypesForMutationsBlock extends AbstractDefaultEnableDisableFunctionalitySchemaConfigBlock
{
    use MainPluginBlockTrait;

    protected function getBlockName(): string
    {
        return 'schema-config-payload-types-for-mutations';
    }

    public function getBlockPriority(): int
    {
        return 10130;
    }

    public function getEnablingModule(): ?string
    {
        return SchemaConfigurationFunctionalityModuleResolver::MUTATIONS;
    }

    protected function getBlockLabel(): string
    {
        return \__('Use payload types for mutations in the schema?', 'gato-graphql');
    }

    protected function getBlockTitle(): string
    {
        return \__('Payload Types for Mutations', 'gato-graphql');
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
