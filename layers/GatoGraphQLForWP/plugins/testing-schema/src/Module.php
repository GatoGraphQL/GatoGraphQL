<?php

declare(strict_types=1);

namespace GatoGraphQL\TestingSchema;

use PoP\Root\Module\ModuleInterface;
use GatoGraphQL\GatoGraphQL\PluginSkeleton\AbstractExtensionModule;

class Module extends AbstractExtensionModule
{
    /**
     * @return array<class-string<ModuleInterface>>
     */
    public function getDependedModuleClasses(): array
    {
        return [
            \PoP\Engine\Module::class,
        ];
    }

    /**
     * Set the default component configuration
     *
     * @param array<string,mixed> $moduleClassConfiguration
     */
    public function customizeModuleClassConfiguration(
        array &$moduleClassConfiguration
    ): void {
        parent::customizeModuleClassConfiguration($moduleClassConfiguration);

        // Enable the AppState Fields
        $moduleClassConfiguration[\PoP\Engine\Module::class][\PoP\Engine\Environment::ENABLE_QUERYING_APP_STATE_FIELDS] = true;
    }
}
