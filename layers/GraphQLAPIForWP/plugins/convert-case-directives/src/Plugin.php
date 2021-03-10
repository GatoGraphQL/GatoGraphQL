<?php

declare(strict_types=1);

namespace GraphQLAPI\ConvertCaseDirectives;

use GraphQLAPI\ConvertCaseDirectives\HybridServices\ModuleResolvers\SchemaModuleResolver;
use GraphQLAPI\ConvertCaseDirectives\PluginScaffolding\AbstractPlugin;

class Plugin extends AbstractPlugin
{
    /**
     * Plugin's namespace
     */
    public const NAMESPACE = __NAMESPACE__;

    /**
     * Plugin main file
     */
    protected function getPluginFile(): string
    {
        return \GRAPHQL_API_CONVERT_CASE_DIRECTIVES_PLUGIN_FILE;
    }

    /**
     * Plugin name
     */
    protected function getPluginName(): string
    {
        return \__('GraphQL API - Convert Case Directives', 'graphql-api-convert-case-directives');
    }

    /**
     * Add Component classes to be initialized
     *
     * @return string[] List of `Component` class to initialize
     */
    public function getComponentClassesToInitialize(): array
    {
        return [
            \GraphQLAPI\ConvertCaseDirectives\Component::class,
        ];
    }

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
