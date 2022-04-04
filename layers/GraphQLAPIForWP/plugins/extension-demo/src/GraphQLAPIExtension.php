<?php

declare(strict_types=1);

namespace GraphQLAPI\ExtensionDemo;

use GraphQLAPI\GraphQLAPI\PluginSkeleton\AbstractExtension;

class GraphQLAPIExtension extends AbstractExtension
{
    /**
     * Plugin's namespace
     */
    public final const NAMESPACE = __NAMESPACE__;

    /**
     * Add Component classes to be initialized
     *
     * @return string[] List of `Component` class to initialize
     */
    public function getComponentClassesToInitialize(): array
    {
        return [
            Component::class,
        ];
    }
}
