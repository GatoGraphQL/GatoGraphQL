<?php

declare(strict_types=1);

namespace GraphQLAPI\PluginSkeleton;

use PoP\Root\Component\AbstractComponent;

/**
 * Initialize component
 */
class Component extends AbstractComponent
{
    /**
     * Classes from PoP components that must be initialized before this component
     *
     * @return string[]
     */
    public static function getDependedComponentClasses(): array
    {
        return [
            \PoP\Engine\Component::class,
        ];
    }
}
