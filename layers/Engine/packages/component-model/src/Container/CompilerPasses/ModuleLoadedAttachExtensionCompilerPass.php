<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Container\CompilerPasses;

use PoP\Root\Module\ApplicationEvents;

class ModuleLoadedAttachExtensionCompilerPass extends AbstractAttachExtensionCompilerPass
{
    protected function getAttachExtensionEvent(): string
    {
        return ApplicationEvents::MODULE_LOADED;
    }

    /**
     * @return array<string,string>
     */
    protected function getAttachableClassGroups(): array
    {
        // Nothing to initialize
        return [];
    }
}
