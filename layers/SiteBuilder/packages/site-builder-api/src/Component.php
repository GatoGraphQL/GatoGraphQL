<?php

declare(strict_types=1);

namespace PoP\SiteBuilderAPI;

use PoP\API\Environment;
use PoP\Root\Component\AbstractComponent;
use PoP\Root\Component\CanDisableComponentTrait;

/**
 * Initialize component
 */
class Component extends AbstractComponent
{
    use CanDisableComponentTrait;

    /**
     * Classes from PoP components that must be initialized before this component
     *
     * @return string[]
     */
    public static function getDependedComponentClasses(): array
    {
        return [
            \PoP\API\Component::class,
        ];
    }

    protected static function resolveEnabled(): bool
    {
        return !Environment::disableAPI();
    }
}
