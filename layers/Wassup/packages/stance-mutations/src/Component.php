<?php

declare(strict_types=1);

namespace PoPSitesWassup\StanceMutations;

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
            \PoPSitesWassup\CustomPostMutations\Component::class,
        ];
    }
}
