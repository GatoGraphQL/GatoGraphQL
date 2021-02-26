<?php

declare(strict_types=1);

namespace GraphQLAPI\ConvertCaseDirectives;

use GraphQLAPI\ConvertCaseDirectives\PluginConfiguration;
use GraphQLAPI\ConvertCaseDirectives\PluginScaffolding\AbstractPlugin;
use GraphQLAPI\ConvertCaseDirectives\HybridServices\ModuleResolvers\SchemaModuleResolver;

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
     * List of ModuleResolver classes used in the plugin
     *
     * @return string[]
     */
    protected function getModuleResolverClasses(): array
    {
        return [
            SchemaModuleResolver::class,
        ];
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
     * Add schema Component classes to skip initializing
     *
     * @return string[] List of `Component` class which must not initialize their Schema services
     */
    public function getSchemaComponentClassesToSkip(): array
    {
        return PluginConfiguration::getSkippingSchemaComponentClasses();
    }
}
