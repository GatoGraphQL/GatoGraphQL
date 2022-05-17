<?php

declare(strict_types=1);

namespace PoPAPI\APIClients;

use PoP\Root\Module\AbstractModule;

class Module extends AbstractModule
{
    /**
     * @return string[]
     */
    public function getDependedModuleClasses(): array
    {
        return [
            \PoPAPI\APIEndpoints\Module::class,
        ];
    }
}
