<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnContext\PROPluginInformation\Services\Blocks;

use GraphQLAPI\GraphQLAPI\ConditionalOnContext\PROPluginInformation\ModuleResolvers\VersioningFunctionalityModuleResolver;

class SchemaConfigFieldDeprecationListBlock extends AbstractSchemaConfigPROPluginPseudoBlock
{
    use PROPluginBlockTrait;

    protected function getBlockName(): string
    {
        return 'schema-config-field-deprecation-lists';
    }

    public function getBlockPriority(): int
    {
        return 10020;
    }

    public function getEnablingModule(): ?string
    {
        return VersioningFunctionalityModuleResolver::FIELD_DEPRECATION;
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
