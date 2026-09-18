<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Settings;

use GatoGraphQL\GatoGraphQL\Facades\Settings\OptionNamespacerFacade;
use GatoGraphQL\GatoGraphQL\PluginSkeleton\PluginOptions;

use function get_option;
use function update_option;
use function wp_cache_delete;

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
    public final const KEY_CUSTOM_POST_TYPES = 'customPostTypes';
    public final const KEY_TAXONOMIES = 'taxonomies';
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
     * The option is read afresh from the database right before it is
     * written, and the change applied over that: two requests writing at
     * once (a feature installing itself while the Settings screen records
     * a preference, say) would otherwise each put back what the other had
     * just stored. The window is narrowed to the write itself, not closed:
     * the Options API offers nothing atomic.
     *
     * @param callable(array<string,mixed>):array<string,mixed> $modify
     */
    protected function modifyInstalledData(callable $modify): void
    {
        $optionName = $this->getOptionName();
        wp_cache_delete($optionName, 'options');
        wp_cache_delete('alloptions', 'options');
        $installedData = $this->getInstalledData();
        update_option($optionName, $modify($installedData));
    }

    /**
     * @return array<string,array<string,mixed>>
     */
    protected function getInstalledFeatures(): array
    {
        return $this->getInstalledFeaturesFrom($this->getInstalledData());
    }

    /**
     * @param array<string,mixed> $installedData
     * @return array<string,array<string,mixed>>
     */
    protected function getInstalledFeaturesFrom(array $installedData): array
    {
        if (!isset($installedData[self::KEY_FEATURES]) || !is_array($installedData[self::KEY_FEATURES])) {
            return [];
        }
        /** @var array<string,array<string,mixed>> */
        return $installedData[self::KEY_FEATURES];
    }

    public function getInstalledFeatureVersion(string $featureSlug): ?string
    {
        $features = $this->getInstalledFeatures();
        $version = $features[$featureSlug][self::KEY_VERSION] ?? null;
        if (!is_scalar($version)) {
            return null;
        }
        return (string) $version;
    }

    /**
     * @param string[] $tableNames
     */
    public function storeInstalledFeatureVersion(string $featureSlug, string $version, array $tableNames = []): void
    {
        $this->modifyInstalledData(function (array $installedData) use ($featureSlug, $version, $tableNames): array {
            $features = $this->getInstalledFeaturesFrom($installedData);
            $features[$featureSlug] = [
                self::KEY_VERSION => $version,
                self::KEY_TABLE_NAMES => array_values($tableNames),
            ];
            $installedData[self::KEY_FEATURES] = $features;
            return $installedData;
        });
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
        if (!isset($this->getInstalledFeatures()[$featureSlug])) {
            return;
        }
        $this->modifyInstalledData(function (array $installedData) use ($featureSlug): array {
            $features = $this->getInstalledFeaturesFrom($installedData);
            unset($features[$featureSlug]);
            $installedData[self::KEY_FEATURES] = $features;
            return $installedData;
        });
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
     * Only the keys handed in are written, over what the option holds at
     * that moment: the identity is recorded from any request and the
     * preferences from the Settings screen, and one writing the whole
     * uninstall record would put back what the other had just changed.
     *
     * @param array<string,mixed> $uninstallDataToMerge
     */
    protected function storeUninstallData(array $uninstallDataToMerge): void
    {
        $this->modifyInstalledData(function (array $installedData) use ($uninstallDataToMerge): array {
            $storedUninstallData = $installedData[self::KEY_UNINSTALL] ?? [];
            if (!is_array($storedUninstallData)) {
                $storedUninstallData = [];
            }
            $installedData[self::KEY_UNINSTALL] = array_merge($storedUninstallData, $uninstallDataToMerge);
            return $installedData;
        });
    }

    /**
     * @param string[] $customPostTypes
     * @param string[] $taxonomies
     */
    public function storeUninstallIdentity(array $customPostTypes, array $taxonomies): void
    {
        $uninstallData = $this->getUninstallData();

        $entityTypeNames = [
            self::KEY_CUSTOM_POST_TYPES => $customPostTypes,
            self::KEY_TAXONOMIES => $taxonomies,
        ];

        $movedEntityTypeNames = [];
        foreach ($entityTypeNames as $key => $currentEntityTypeNames) {
            $storedEntityTypeNames = $uninstallData[$key] ?? [];
            if (!is_array($storedEntityTypeNames)) {
                $storedEntityTypeNames = [];
            }
            sort($storedEntityTypeNames);

            /**
             * An entity type registered by an extension which has just been
             * deactivated must stay recorded, or uninstalling would leave its
             * entries behind. So the list only ever grows.
             */
            $mergedEntityTypeNames = array_values(array_unique(array_merge(
                $storedEntityTypeNames,
                $currentEntityTypeNames
            )));
            sort($mergedEntityTypeNames);

            if ($mergedEntityTypeNames === $storedEntityTypeNames) {
                continue;
            }
            $movedEntityTypeNames[$key] = $mergedEntityTypeNames;
        }

        /**
         * The identity is recorded on every request, so only write when it
         * has actually moved.
         */
        if ($movedEntityTypeNames === []) {
            return;
        }

        $this->storeUninstallData($movedEntityTypeNames);
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
        $this->storeUninstallData([
            self::KEY_DELETE_DATA => $deleteDataOnUninstall,
            self::KEY_DELETE_CONTENT => $deleteContentOnUninstall,
        ]);
    }
}
