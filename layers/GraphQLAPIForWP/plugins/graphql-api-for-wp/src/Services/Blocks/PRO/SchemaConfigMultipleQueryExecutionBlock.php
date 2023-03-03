<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks\PRO;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\PRO\SchemaConfigurationFunctionalityModuleResolver;

class SchemaConfigMultipleQueryExecutionBlock extends AbstractSchemaConfigPROPseudoBlock
{
    use PROPluginBlockTrait;

    protected function getBlockName(): string
    {
        return 'schema-config-multiple-query-execution';
    }

    public function getBlockPriority(): int
    {
        return 2500;
    }

    public function getEnablingModule(): ?string
    {
        return SchemaConfigurationFunctionalityModuleResolver::MULTIPLE_QUERY_EXECUTION;
    }

    // protected function getBlockLabel(): string
    // {
    //     return \__('Enable multiple query execution?', 'graphql-api-pro');
    // }

    // protected function getBlockTitle(): string
    // {
    //     return \__('Multiple Query Execution', 'graphql-api-pro');
    // }

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

    // /**
    //  * Register style-index.css
    //  */
    // protected function registerCommonStyleCSS(): bool
    // {
    //     return true;
    // }
}
