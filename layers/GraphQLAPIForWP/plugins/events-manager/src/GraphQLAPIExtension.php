<?php

declare(strict_types=1);

namespace GraphQLAPI\EventsManager;

use GraphQLAPI\EventsManager\ModuleResolvers\SchemaModuleResolver;
use GraphQLAPI\GraphQLAPI\PluginSkeleton\AbstractExtension;

class GraphQLAPIExtension extends AbstractExtension
{
    /**
     * Plugin's namespace
     */
    public const NAMESPACE = __NAMESPACE__;

    /**
     * Add Component classes to be initialized
     *
     * @return string[] List of `Component` class to initialize
     */
    public function getComponentClassesToInitialize(): array
    {
        return [
            \GraphQLAPI\EventsManager\Component::class,
        ];
    }

    /**
     * Provide the list of modules to check if they are enabled and,
     * if they are not, what component classes must skip initialization
     *
     * @return array<string,string[]>
     */
    protected function getModuleComponentClasses(): array
    {
        return [
            SchemaModuleResolver::SCHEMA_EVENTS => [
                \PoPSchema\Locations\Component::class,
                \PoPSchema\Events\Component::class,
            ],
        ];
    }
}
