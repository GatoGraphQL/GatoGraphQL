<?php

declare(strict_types=1);

namespace PoPAPI\APIClients;

use PoP\Root\Module\AbstractModule;

class Module extends AbstractModule
{
    /**
     * @return array<class-string<\PoP\Root\Module\ModuleInterface>>
     */
    public function getDependedModuleClasses(): array
    {
        return [
            \PoPAPI\APIEndpoints\Module::class,
        ];
    }
}
