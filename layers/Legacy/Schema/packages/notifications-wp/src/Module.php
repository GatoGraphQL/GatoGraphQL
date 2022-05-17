<?php

declare(strict_types=1);

namespace PoPSchema\NotificationsWP;

use PoP\Root\Module\AbstractComponent;

/**
 * Initialize component
 */
class Module extends AbstractComponent
{
    /**
     * Classes from PoP components that must be initialized before this component
     *
     * @return string[]
     */
    public function getDependedComponentClasses(): array
    {
        return [
            \PoPSchema\Notifications\Module::class,
            \PoPSchema\SchemaCommons\Module::class,
        ];
    }
}
