<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations;

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
            \PoPSchema\CustomPosts\Component::class,
            \PoPSchema\Tags\Component::class,
            \PoPSchema\UserState\Component::class,
        ];
    }
}
