<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\PluginSkeleton;

use PoP\Root\Module\ModuleInterface;

abstract class AbstractBundleExtensionModule extends AbstractExtensionModule
{
    /**
     * The Extension Bundle normally has no Modules
     * (but already contains all of its logic via its
     * bundled extensions).
     *
     * It must NOT initialize the Modules from the
     * contained Plugins (those will be initialized
     * when including the plugin's main file).
     *
     * @return array<class-string<ModuleInterface>>
     */
    public function getDependedModuleClasses(): array
    {
        return [];
    }
}
