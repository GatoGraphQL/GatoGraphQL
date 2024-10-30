<?php

declare(strict_types=1);

namespace GatoGraphQLStandalone\GatoGraphQL\PluginSkeleton;

use GatoGraphQL\GatoGraphQL\Module;
use PoP\Root\Module\ModuleInterface;

/**
 * This class is not used by Gato GraphQL.
 *
 * Instead, it is a convenience class for standalone plugins,
 * to supersede GatoGraphQL\GatoGraphQL\Plugin
 */
abstract class AbstractStandalonePluginModule extends Module
{
    /**
     * Indicate if this Module must also be registered
     * under the classes of other Modules. Needed by
     * standalone plugins to supersede
     * GatoGraphQL\GatoGraphQL\Module and yet be able to
     * get configuration values from that module.
     *
     * @return array<class-string<ModuleInterface>>
     */
    public function registerAsModules(): array
    {
        return [
            Module::class,
        ];
    }

    protected function getModuleConfigurationClassname(): string
    {
        return 'StandaloneModuleConfiguration';
    }

    /**
     * Set the default module configuration
     *
     * @param array<string,mixed> $moduleClassConfiguration
     */
    public function customizeModuleClassConfiguration(
        array &$moduleClassConfiguration
    ): void {
        parent::customizeModuleClassConfiguration($moduleClassConfiguration);

        if ($this->allowQueryingPrivateCPTs()) {
            $moduleClassConfiguration[\PoPCMSSchema\CustomPosts\Module::class][\PoPCMSSchema\CustomPosts\Environment::ALLOW_QUERYING_PRIVATE_CPTS] = true;
        }
    }

    protected function allowQueryingPrivateCPTs(): bool
    {
        return false;
    }
}
