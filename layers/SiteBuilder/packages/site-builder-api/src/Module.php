<?php

declare(strict_types=1);

namespace PoP\SiteBuilderAPI;

use PoPAPI\API\Environment;
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

    protected function resolveEnabled(): bool
    {
        return !Environment::disableAPI();
    }
}
