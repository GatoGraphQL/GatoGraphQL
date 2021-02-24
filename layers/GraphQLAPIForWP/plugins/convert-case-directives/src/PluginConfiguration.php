<?php

declare(strict_types=1);

namespace GraphQLAPI\ConvertCaseDirectives;

use GraphQLAPI\ConvertCaseDirectives\SystemServices\ModuleResolvers\SchemaModuleResolver;
use GraphQLAPI\ConvertCaseDirectives\PluginScaffolding\AbstractPluginConfiguration;

class PluginConfiguration extends AbstractPluginConfiguration
{
    /**
     * Provide the list of modules to check if they are enabled and,
     * if they are not, what component classes must skip initialization
     *
     * @return array
     */
    protected static function getModuleComponentClasses(): array
    {
        return [
            SchemaModuleResolver::CONVERT_CASE_DIRECTIVES => [
                \PoPSchema\ConvertCaseDirectives\Component::class,
            ],
        ];
    }
}
