<?php

declare(strict_types=1);

namespace PoP\SSG;

use PoP\Root\Module\AbstractModule;

class Module extends AbstractModule
{
    /**
     * @return string[]
     */
    public function getDependedModuleClasses(): array
    {
        return [
            \PoP\Site\Module::class,
        ];
    }
}
