<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Settings;

use GatoGraphQL\GatoGraphQL\Facades\Settings\OptionNamespacerFacade;
use GatoGraphQL\GatoGraphQL\PluginSkeleton\PluginOptions;

use function get_option;
use function update_option;

/**
 * The single entry recording what the plugin has installed on the site.
 *
 * It holds two halves which are written at different times: the version of
 * each feature's data, updated whenever that feature installs itself, and
 * everything `uninstall.php` needs to remove the plugin's data again.
 *
 * The option is deliberately left out of {@see Options} and out of the lists
 * that "Reset Settings" empties: resetting the user's Settings must not claim
 * that a table which still exists has never been installed, nor silently turn
 * off the user's choice to have their data deleted.
 */
class InstalledDataSettingsManager implements InstalledDataSettingsManagerInterface
{
    public final const KEY_FEATURES = 'features';
    public final const KEY_VERSION = 'version';
    public final const KEY_TABLE_NAMES = 'tableNames';
    public final const KEY_UNINSTALL = 'uninstall';
    public final const KEY_PLUGIN_NAMESPACE = 'namespace';
    public final const KEY_CUSTOM_POST_TYPES = 'customPostTypes';
    public final const KEY_DELETE_DATA = 'deleteData';
    public final const KEY_DELETE_CONTENT = 'deleteContent';

    private ?OptionNamespacerInterface $optionNamespacer = null;

    final protected function getOptionNamespacer(): OptionNamespacerInterface
    {
        return $this->optionNamespacer ??= OptionNamespacerFacade::getInstance();
    }

    protected function getOptionName(): string
    {
        return $this->getOptionNamespacer()->namespaceOption(PluginOptions::INSTALLED_DATA);
    }

    /**
     * @return array<string,mixed>
     */
    protected function getInstalledData(): array
    {
        $installedData = get_option($this->getOptionName(), []);
        if (!is_array($installedData)) {
            return [];
        }
        /** @var array<string,mixed> */
        return $installedData;
    }

    /**
     * @param array<string,mixed> $installedData
     */
    protected function storeInstalledData(array $installedData): void
    {
        update_option($this->getOptionName(), $installedData);
    }

    /**
     * @return array<string,array<string,mixed>>
     */
    protected function getInstalledFeatures(): array
    {
        $installedData = $this->getInstalledData();
        if (!isset($installedData[self::KEY_FEATURES]) || !is_array($installedData[self::KEY_FEATURES])) {
            return [];
        }
        /** @var array<string,array<string,mixed>> */
        return $installedData[self::KEY_FEATURES];
    }

    public function getInstalledFeatureVersion(string $featureSlug): ?string
    {
        $features = $this->getInstalledFeatures();
        if (!isset($features[$featureSlug][self::KEY_VERSION])) {
            return null;
        }
        return (string) $features[$featureSlug][self::KEY_VERSION];
    }

    /**
     * @param string[] $tableNames
     */
    public function storeInstalledFeatureVersion(string $featureSlug, string $version, array $tableNames = []): void
    {
        $installedData = $this->getInstalledData();
        $features = $this->getInstalledFeatures();
        $features[$featureSlug] = [
            self::KEY_VERSION => $version,
            self::KEY_TABLE_NAMES => array_values($tableNames),
        ];
        $installedData[self::KEY_FEATURES] = $features;
        $this->storeInstalledData($installedData);
    }

    /**
     * @return string[]
     */
    public function getInstalledTableNames(): array
    {
        $tableNames = [];
        foreach ($this->getInstalledFeatures() as $feature) {
            if (!isset($feature[self::KEY_TABLE_NAMES]) || !is_array($feature[self::KEY_TABLE_NAMES])) {
                continue;
            }
            foreach ($feature[self::KEY_TABLE_NAMES] as $tableName) {
                $tableNames[] = (string) $tableName;
            }
        }
        return array_values(array_unique($tableNames));
    }

    public function removeInstalledFeatureVersion(string $featureSlug): void
    {
        $features = $this->getInstalledFeatures();
        if (!isset($features[$featureSlug])) {
            return;
        }
        unset($features[$featureSlug]);
        $installedData = $this->getInstalledData();
        $installedData[self::KEY_FEATURES] = $features;
        $this->storeInstalledData($installedData);
    }

    /**
     * @return array<string,mixed>
     */
    protected function getUninstallData(): array
    {
        $installedData = $this->getInstalledData();
        if (!isset($installedData[self::KEY_UNINSTALL]) || !is_array($installedData[self::KEY_UNINSTALL])) {
            return [];
        }
        /** @var array<string,mixed> */
        return $installedData[self::KEY_UNINSTALL];
    }

    /**
     * @param array<string,mixed> $uninstallData
     */
    protected function storeUninstallData(array $uninstallData): void
    {
        $installedData = $this->getInstalledData();
        $installedData[self::KEY_UNINSTALL] = $uninstallData;
        $this->storeInstalledData($installedData);
    }

    /**
     * @param string[] $customPostTypes
     */
    public function storeUninstallIdentity(
        string $pluginNamespace,
        array $customPostTypes,
    ): void {
        $uninstallData = $this->getUninstallData();
        $identity = [
            self::KEY_PLUGIN_NAMESPACE => $pluginNamespace,
            self::KEY_CUSTOM_POST_TYPES => $customPostTypes,
        ];

        /**
         * The identity is recorded on every request, so only write when it
         * has actually moved: a custom post type registered by a plugin
         * which has just been deactivated must still be recorded, or
         * uninstalling would leave its entries behind.
         */
        $storedIdentity = [
            self::KEY_PLUGIN_NAMESPACE => $uninstallData[self::KEY_PLUGIN_NAMESPACE] ?? null,
            self::KEY_CUSTOM_POST_TYPES => $uninstallData[self::KEY_CUSTOM_POST_TYPES] ?? [],
        ];
        if (!is_array($storedIdentity[self::KEY_CUSTOM_POST_TYPES])) {
            $storedIdentity[self::KEY_CUSTOM_POST_TYPES] = [];
        }
        $identity[self::KEY_CUSTOM_POST_TYPES] = array_values(array_unique(array_merge(
            $storedIdentity[self::KEY_CUSTOM_POST_TYPES],
            $customPostTypes
        )));
        sort($identity[self::KEY_CUSTOM_POST_TYPES]);
        sort($storedIdentity[self::KEY_CUSTOM_POST_TYPES]);
        if ($identity === $storedIdentity) {
            return;
        }

        $this->storeUninstallData(array_merge($uninstallData, $identity));
    }

    public function getDeleteDataOnUninstall(): bool
    {
        return (bool) ($this->getUninstallData()[self::KEY_DELETE_DATA] ?? false);
    }

    public function getDeleteContentOnUninstall(): bool
    {
        return (bool) ($this->getUninstallData()[self::KEY_DELETE_CONTENT] ?? false);
    }

    public function storeUninstallPreferences(
        bool $deleteDataOnUninstall,
        bool $deleteContentOnUninstall,
    ): void {
        $uninstallData = $this->getUninstallData();
        $uninstallData[self::KEY_DELETE_DATA] = $deleteDataOnUninstall;
        $uninstallData[self::KEY_DELETE_CONTENT] = $deleteContentOnUninstall;
        $this->storeUninstallData($uninstallData);
    }
}
