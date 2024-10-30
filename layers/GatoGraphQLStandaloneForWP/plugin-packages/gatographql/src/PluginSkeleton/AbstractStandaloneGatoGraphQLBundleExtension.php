<?php

declare(strict_types=1);

namespace GatoGraphQLStandalone\GatoGraphQL\PluginSkeleton;

use GatoGraphQLStandalone\GatoGraphQL\Module;
use GatoGraphQLStandalone\GatoGraphQL\ModuleConfiguration;
use GatoGraphQL\GatoGraphQL\PluginSkeleton\AbstractGatoGraphQLBundleExtension;
use PoP\ComponentModel\App;

use function add_action;

abstract class AbstractStandaloneGatoGraphQLBundleExtension extends AbstractGatoGraphQLBundleExtension
{
    /**
     * Regenerate the configuration when the settings are updated
     *
     * @see wp-content/plugins/polylang/settings/settings-module.php
     */
    protected function doSetup(): void
    {
        parent::doSetup();

        $optionNames = $this->getInstallPluginSetupDataFormOptionNames();
        $this->maybeInstallPluginSetupDataWhenSettingsCategoriesOptionFormsUpdated($optionNames);
    }

    /**
     * @return string[]
     */
    protected function getInstallPluginSetupDataFormOptionNames(): array
    {
        return [];
    }

    /**
     * @param string[] $optionNames
     */
    protected function maybeInstallPluginSetupDataWhenSettingsCategoriesOptionFormsUpdated(array $optionNames): void
    {
        foreach ($optionNames as $optionName) {
            add_action(
                "update_option_{$optionName}",
                function(): void {
                    if ($this->disableAutomaticConfigUpdates()) {
                        return;
                    }
                    
                    $this->installPluginSetupDataWhenSettingsCategoriesOptionFormsUpdated();
                }
            );
        }
    }
    
    protected function disableAutomaticConfigUpdates(): bool
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return $moduleConfiguration->disableAutomaticConfigUpdates();
    }

    protected function installPluginSetupDataWhenSettingsCategoriesOptionFormsUpdated(): void
    {
        $this->installPluginSetupData();
    }
}
