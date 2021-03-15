<?php

declare(strict_types=1);

namespace GraphQLAPI\ConvertCaseDirectives;

use GraphQLAPI\ConvertCaseDirectives\HybridServices\ModuleResolvers\SchemaModuleResolver;
use GraphQLAPI\PluginSkeleton\AbstractExtension;

class GraphQLAPIExtension extends AbstractExtension
{
    /**
     * Plugin's namespace
     */
    public const NAMESPACE = __NAMESPACE__;

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
    protected function getModuleComponentClasses(): array
    {
        return [
            SchemaModuleResolver::CONVERT_CASE_DIRECTIVES => [
                \PoPSchema\ConvertCaseDirectives\Component::class,
            ],
        ];
    }
}
