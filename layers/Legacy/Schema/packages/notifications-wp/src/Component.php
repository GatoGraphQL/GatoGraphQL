<?php

declare(strict_types=1);

namespace PoPSchema\NotificationsWP;

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
    public function getDependedComponentClasses(): array
    {
        return [
            \PoPSchema\Notifications\Component::class,
            \PoP\EngineWP\Component::class,
        ];
    }
}
