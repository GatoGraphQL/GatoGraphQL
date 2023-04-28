<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnContext\PROPluginInformation\Services\Blocks;

use GraphQLAPI\GraphQLAPI\ConditionalOnContext\PROPluginInformation\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;

class SchemaConfigComposableDirectivesBlock extends AbstractSchemaConfigPROPluginPseudoBlock
{
    use PROPluginBlockTrait;

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
