<?php

declare(strict_types=1);

namespace PoPAPI\APIClients;

use PoP\Root\Module\AbstractModule;

/**
 * Initialize component
 */
class Module extends AbstractModule
{
    /**
     * Classes from PoP components that must be initialized before this component
     *
     * @return string[]
     */
    public function getDependedModuleClasses(): array
    {
        return [
            \PoPAPI\APIEndpoints\Module::class,
        ];
    }
}
