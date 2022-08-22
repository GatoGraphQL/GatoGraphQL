<?php

declare(strict_types=1);

namespace PoP\SiteBuilderAPI;

use PoP\Root\Module\ModuleInterface;
use PoPAPI\API\Environment;
use PoP\Root\Module\AbstractModule;

class Module extends AbstractModule
{
    /**
     * @return array<class-string<ModuleInterface>>
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
