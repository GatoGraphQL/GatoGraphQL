<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Settings;

interface InstalledDataSettingsManagerInterface
{
    /**
     * The version of the feature's data currently installed on the site,
     * or `null` when the feature has never been installed.
     */
    public function getInstalledFeatureVersion(string $featureSlug): ?string;

    public function storeInstalledFeatureVersion(string $featureSlug, string $version): void;

    public function removeInstalledFeatureVersion(string $featureSlug): void;

    /**
     * Record what identifies this plugin's data, so that `uninstall.php`
     * can find it without the plugin being bootstrapped.
     *
     * @param string[] $customPostTypes
     */
    public function storeUninstallIdentity(
        string $pluginNamespace,
        string $dbNamespace,
        array $customPostTypes,
    ): void;

    public function getDeleteDataOnUninstall(): bool;

    public function getDeleteContentOnUninstall(): bool;

    public function storeUninstallPreferences(
        bool $deleteDataOnUninstall,
        bool $deleteContentOnUninstall,
    ): void;
}
