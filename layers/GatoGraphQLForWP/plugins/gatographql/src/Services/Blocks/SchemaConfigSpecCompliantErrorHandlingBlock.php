<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\Blocks;

use GatoGraphQL\GatoGraphQL\Services\Blocks\AbstractDefaultEnableDisableFunctionalitySchemaConfigBlock;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;

class SchemaConfigSpecCompliantErrorHandlingBlock extends AbstractDefaultEnableDisableFunctionalitySchemaConfigBlock
{
    use MainPluginBlockTrait;

    protected function getBlockName(): string
    {
        return 'schema-config-spec-compliant-error-handling';
    }

    public function getBlockPriority(): int
    {
        return 10100;
    }

    public function getEnablingModule(): ?string
    {
        return SchemaConfigurationFunctionalityModuleResolver::SPEC_COMPLIANT_ERROR_HANDLING;
    }

    protected function getBlockLabel(): string
    {
        return \__('Handle errors as in the GraphQL spec?', 'gatographql');
    }

    protected function getBlockTitle(): string
    {
        return \__('Spec-Compliant Error Handling', 'gatographql');
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
