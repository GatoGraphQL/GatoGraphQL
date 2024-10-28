<?php

declare(strict_types=1);

namespace GatoGraphQLStandalone\GatoGraphQL\PluginSkeleton;

use GatoGraphQL\GatoGraphQL\PluginSkeleton\AbstractBundleExtensionModule;
use PoP\Root\Module\ModuleInterface;

abstract class AbstractStandaloneBundleExtensionModule extends AbstractBundleExtensionModule
{
    /**
     * @return array<class-string<ModuleInterface>>
     */
    public function getDependedModuleClasses(): array
    {
        return array_merge(
            parent::getDependedModuleClasses(),
            [
                \GatoGraphQLStandalone\GatoGraphQL\Module::class,
            ]
        );
    }
}
