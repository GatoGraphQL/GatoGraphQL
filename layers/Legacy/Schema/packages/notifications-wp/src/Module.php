<?php

declare(strict_types=1);

namespace PoPSchema\NotificationsWP;

use PoP\Root\Module\AbstractModule;

class Module extends AbstractModule
{
    /**
     * @return string[]
     */
    public function getDependedModuleClasses(): array
    {
        return [
            \PoPSchema\Notifications\Module::class,
            \PoPSchema\SchemaCommons\Module::class,
        ];
    }
}
