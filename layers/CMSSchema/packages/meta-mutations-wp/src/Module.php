<?php

declare(strict_types=1);

namespace PoPCMSSchema\MetaMutationsWP;

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
            \PoPCMSSchema\MetaMutations\Module::class,
            \PoPCMSSchema\UserRolesWP\Module::class,
            \PoPCMSSchema\UserStateMutationsWP\Module::class,
        ];
    }
}
