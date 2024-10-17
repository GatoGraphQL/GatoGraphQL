<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\PluginSkeleton;

use GatoGraphQL\GatoGraphQL\PluginSkeleton\AbstractGatoGraphQLBundleExtension;
use PoP\Root\Module\ModuleInterface;

abstract class AbstractStandaloneGatoGraphQLExtensionBundle extends AbstractGatoGraphQLBundleExtension
{
    /**
     * Do not initialize any Module, as that is already
     * done by the corresponding StandalonePlugin
     *
     * @return array<class-string<ModuleInterface>> List of `Module` class to initialize
     */
    protected function getModuleClassesToInitialize(): array
    {
        return [];
    }
}
