<?php

declare(strict_types=1);

namespace GraphQLAPI\ExtensionDemo;

use GraphQLAPI\GraphQLAPI\PluginSkeleton\AbstractExtensionComponent;

/**
 * Initialize component
 */
class Component extends AbstractExtensionComponent
{
    public static function getDependedComponentClasses(): array
    {
        return [
            \PoPSchema\BasicDirectives\Component::class,
        ];
    }
}
