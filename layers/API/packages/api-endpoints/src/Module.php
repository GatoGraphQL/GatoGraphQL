<?php

declare(strict_types=1);

namespace PoPAPI\APIEndpoints;

use PoP\Root\Module\AbstractModule;

class Module extends AbstractModule
{
    /**
     * @return string[]
     */
    public function getDependedModuleClasses(): array
    {
        return [
            \PoPAPI\API\Module::class,
        ];
    }
}
