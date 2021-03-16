<?php

declare(strict_types=1);

namespace GraphQLAPI\ConvertCaseDirectives;

use GraphQLAPI\PluginSkeleton\AbstractExtensionComponent;

/**
 * Initialize component
 */
class Component extends AbstractExtensionComponent
{
    public static function getDependedComponentClasses(): array
    {
        return [
            \PoPSchema\ConvertCaseDirectives\Component::class,
        ];
    }
}
