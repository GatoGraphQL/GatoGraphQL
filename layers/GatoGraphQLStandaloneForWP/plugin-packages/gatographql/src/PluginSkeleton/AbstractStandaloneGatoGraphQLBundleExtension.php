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
     * Install plugin setup data after a commercial license
     * has been activated
     */
    public function anyCommercialLicenseJustActivated(): void
    {
        parent::anyCommercialLicenseJustActivated();

        // Do on "init" so the taxonomies have been registered
        add_action(
            'init',
            $this->maybeInstallPluginSetupDataAfterCommercialLicenseActivated(...)
        );
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
                function () use ($optionName): void {
                    if ($this->disableAutomaticConfigUpdates()) {
                        return;
                    }

                    $this->installPluginSetupDataWhenSettingsCategoriesOptionFormsUpdated($optionName);
                }
            );
        }
    }

    /**
     * Instead of reacting on Options::PLUGIN_MANAGEMENT, trigger
     * installing data on the first request after the license is activated
     */
    protected function maybeInstallPluginSetupDataAfterCommercialLicenseActivated(): void
    {
        if (
            !$this->doInstallPluginSetupDataAfterCommercialLicenseActivated()
            || $this->disableAutomaticConfigUpdates()
        ) {
            return;
        }

        $this->installPluginSetupDataWhenSettingsCategoriesOptionFormsUpdated(null);
    }

    protected function doInstallPluginSetupDataAfterCommercialLicenseActivated(): bool
    {
        return true;
    }

    protected function disableAutomaticConfigUpdates(): bool
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return $moduleConfiguration->disableAutomaticConfigUpdates();
    }

    protected function installPluginSetupDataWhenSettingsCategoriesOptionFormsUpdated(string|null $optionName): void
    {
        $this->installPluginSetupData();
    }
}
