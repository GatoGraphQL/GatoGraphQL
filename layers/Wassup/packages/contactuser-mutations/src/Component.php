<?php

declare(strict_types=1);

namespace PoPSitesWassup\ContactUserMutations;

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
            \PoPSchema\Users\Component::class,
            \PoPSitesWassup\FormMutations\Component::class,
        ];
    }
}
