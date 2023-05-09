<?php

declare(strict_types=1);

namespace GatoGraphQL\TestingSchema;

use GatoGraphQL\GatoGraphQL\PluginSkeleton\AbstractExtensionModule;
use GatoGraphQL\TestingSchema\Constants\Actions;
use PoP\Root\Module\ModuleInterface;

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

        // Maybe enable the AppState Fields
        // phpcs:disable SlevomatCodingStandard.Variables.DisallowSuperGlobalVariable.DisallowedSuperGlobalVariable
        $actions = $_GET['actions'] ?? null;
        if ($actions === null || !is_array($actions)) {
            return;
        }
        /** @var string[] $actions */
        if (in_array(Actions::ENABLE_APP_STATE_FIELDS, $actions)) {
            $moduleClassConfiguration[\PoP\Engine\Module::class][\PoP\Engine\Environment::ENABLE_QUERYING_APP_STATE_FIELDS] = true;
        }
    }
}
