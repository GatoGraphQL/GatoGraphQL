<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnContext\PROPluginPseudoModules\Services\Blocks;

use GraphQLAPI\GraphQLAPI\ConditionalOnContext\PROPluginPseudoModules\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;

class SchemaConfigMultipleQueryExecutionBlock extends AbstractSchemaConfigPROPluginPseudoBlock
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
