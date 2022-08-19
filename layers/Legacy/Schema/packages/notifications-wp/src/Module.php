<?php

declare(strict_types=1);

namespace PoPSchema\NotificationsWP;

use PoP\Root\Module\ModuleInterface;
use PoP\Root\Module\AbstractModule;

class Module extends AbstractModule
{
    /**
     * @return array<class-string<ModuleInterface>>
     */
    public function getDependedModuleClasses(): array
    {
        return [
            \PoPSchema\Notifications\Module::class,
            \PoPSchema\SchemaCommons\Module::class,
        ];
    }
}
