<?php

declare(strict_types=1);

namespace PoP\MandatoryDirectivesByConfiguration;

use PoP\Root\Module\AbstractModule;

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
            \PoP\ComponentModel\Module::class,
        ];
    }
}
