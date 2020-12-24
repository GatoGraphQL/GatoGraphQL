<?php

declare(strict_types=1);

namespace GraphQLAPI\ConvertCaseDirectives;

use PoP\Engine\ComponentLoader;
use GraphQLAPI\ConvertCaseDirectives\PluginConfiguration;
use GraphQLAPI\ConvertCaseDirectives\PluginScaffolding\AbstractPlugin;
use GraphQLAPI\ConvertCaseDirectives\ModuleResolvers\SchemaModuleResolver;

class Plugin extends AbstractPlugin
{
    /**
     * Plugin's namespace
     */
    public const NAMESPACE = __NAMESPACE__;

    /**
     * Plugin main file
     *
     * @return string
     */
    protected function getPluginFile(): string
    {
        return \GRAPHQL_API_CONVERT_CASE_DIRECTIVES_PLUGIN_FILE;
    }

    /**
     * Plugin name
     *
     * @return string
     */
    protected function getPluginName(): string
    {
        return \__('GraphQL API - Convert Case Directives', 'graphql-api-convert-case-directives');
    }

    /**
     * List of ModuleResolver classes used in the plugin
     *
     * @return array
     */
    protected function getModuleResolverClasses(): array
    {
        return [
            SchemaModuleResolver::class,
        ];
    }

    /**
     * Plugin set-up
     *
     * @return void
     */
    public function doInitialize(): void
    {
        // Component configuration
        $skipSchemaComponentClasses = PluginConfiguration::getSkippingSchemaComponentClasses();

        // Initialize the plugin's Component and, with it, all its dependencies from PoP
        ComponentLoader::initializeComponents(
            [
                \PoP\ConvertCaseDirectives\Component::class,
            ],
            [],
            $skipSchemaComponentClasses
        );
    }
}
