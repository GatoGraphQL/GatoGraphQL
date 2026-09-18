<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\PluginSkeleton;

use GatoGraphQL\GatoGraphQL\Settings\InstalledDataSettingsManager;

use function get_option;
use function get_sites;
use function is_multisite;
use function restore_current_blog;
use function switch_to_blog;

/**
 * Remove everything the plugin has stored on the site, when the user has
 * asked for it, as the plugin is being deleted.
 *
 * This class runs from `uninstall.php`, i.e. with WordPress loaded but the
 * plugin not bootstrapped: there is no container, no service, and nothing
 * which can tell it the plugin's namespace. It is given none of that, and
 * instead finds the single option the plugin records it all under
 * {@see PluginOptions::INSTALLED_DATA}, searching for it by a suffix which
 * is the same whatever the plugin is called.
 *
 * Everything the plugin writes is namespaced already, by the option, meta
 * and database namespacers alike, so the data is removed by matching that
 * namespace rather than by keeping a list of what each feature created.
 * A feature added later is therefore removed too, without having to
 * remember to register it here.
 */
class PluginUninstaller
{
    /**
     * Remove the plugin's data from every site it was installed on, if the
     * user asked for that. Called from `uninstall.php`.
     */
    public static function uninstall(): void
    {
        $optionName = self::findInstalledDataOptionName();
        if ($optionName === null) {
            return;
        }

        $installedData = get_option($optionName);
        if (!is_array($installedData)) {
            return;
        }

        $uninstallData = $installedData[InstalledDataSettingsManager::KEY_UNINSTALL] ?? null;
        if (!is_array($uninstallData)) {
            return;
        }

        if (!($uninstallData[InstalledDataSettingsManager::KEY_DELETE_DATA] ?? false)) {
            return;
        }

        $pluginNamespace = (string) ($uninstallData[InstalledDataSettingsManager::KEY_PLUGIN_NAMESPACE] ?? '');
        $dbNamespace = (string) ($uninstallData[InstalledDataSettingsManager::KEY_DB_NAMESPACE] ?? '');
        if ($pluginNamespace === '' || $dbNamespace === '') {
            return;
        }

        $deleteContent = (bool) ($uninstallData[InstalledDataSettingsManager::KEY_DELETE_CONTENT] ?? false);
        $customPostTypes = $uninstallData[InstalledDataSettingsManager::KEY_CUSTOM_POST_TYPES] ?? [];
        if (!is_array($customPostTypes)) {
            $customPostTypes = [];
        }
        /** @var string[] $customPostTypes */

        /**
         * Options, meta and tables are all per-site, and WordPress runs
         * `uninstall.php` only for the site the plugin is being deleted
         * from, so a network-wide delete would otherwise leave every other
         * site's data behind.
         */
        if (!is_multisite()) {
            self::uninstallFromCurrentSite($pluginNamespace, $dbNamespace, $customPostTypes, $deleteContent);
            return;
        }

        /** @var int[] */
        $sites = get_sites(['fields' => 'ids', 'number' => 0]);
        foreach ($sites as $siteID) {
            switch_to_blog($siteID);
            self::uninstallFromCurrentSite($pluginNamespace, $dbNamespace, $customPostTypes, $deleteContent);
            restore_current_blog();
        }
    }

    /**
     * The option is found by suffix, and not by name, because the name
     * carries the plugin's namespace, which is exactly what this class
     * cannot know before reading it.
     */
    protected static function findInstalledDataOptionName(): ?string
    {
        global $wpdb;

        /** @var string|null */
        $optionName = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE %s ORDER BY option_id ASC LIMIT 1",
                '%' . $wpdb->esc_like(PluginOptions::INSTALLED_DATA_OPTION_SUFFIX)
            )
        );
        if ($optionName === null || $optionName === '') {
            return null;
        }
        return $optionName;
    }

    /**
     * @param string[] $customPostTypes
     */
    protected static function uninstallFromCurrentSite(
        string $pluginNamespace,
        string $dbNamespace,
        array $customPostTypes,
        bool $deleteContent,
    ): void {
        if ($deleteContent && $customPostTypes !== []) {
            self::deleteCustomPosts($customPostTypes);
        }
        self::dropTables($dbNamespace);
        self::deleteMeta($pluginNamespace);
        self::deleteOptions($pluginNamespace);
    }

    /**
     * Tables are created as `{$wpdb->prefix}{$dbNamespace}_{name}`
     * {@see AbstractPlugin::getPluginNamespaceForDB()}.
     */
    protected static function dropTables(string $dbNamespace): void
    {
        global $wpdb;

        $tableNamePattern = $wpdb->esc_like($wpdb->prefix . $dbNamespace . '_') . '%';
        /** @var string[] */
        $tableNames = $wpdb->get_col(
            $wpdb->prepare('SHOW TABLES LIKE %s', $tableNamePattern)
        );
        foreach ($tableNames as $tableName) {
            $wpdb->query("DROP TABLE IF EXISTS `{$tableName}`"); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
        }
    }

    /**
     * Meta keys are namespaced, and prefixed with an underscore when they
     * are not to be shown to the user
     * {@see \GatoGraphQL\GatoGraphQL\Meta\MetaNamespacer::namespaceMetaKey()},
     * so both spellings are removed.
     */
    protected static function deleteMeta(string $pluginNamespace): void
    {
        global $wpdb;

        $metaTableNames = [
            $wpdb->postmeta,
            $wpdb->termmeta,
            $wpdb->usermeta,
            $wpdb->commentmeta,
        ];
        $metaKeyPattern = $wpdb->esc_like($pluginNamespace . '-') . '%';
        $underscoredMetaKeyPattern = $wpdb->esc_like('_' . $pluginNamespace . '-') . '%';
        foreach ($metaTableNames as $metaTableName) {
            $wpdb->query(
                $wpdb->prepare(
                    "DELETE FROM {$metaTableName} WHERE meta_key LIKE %s OR meta_key LIKE %s", // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
                    $metaKeyPattern,
                    $underscoredMetaKeyPattern
                )
            );
        }
    }

    /**
     * Options are namespaced {@see \GatoGraphQL\GatoGraphQL\Settings\OptionNamespacer::namespaceOption()},
     * but the transients built on top of them are not: WordPress stores those
     * under its own prefixes, so they are matched separately.
     *
     * This removes the plugin's record of its own installed data too, which
     * is wanted: the plugin is going away, and the entry is of no use to
     * anything that remains.
     */
    protected static function deleteOptions(string $pluginNamespace): void
    {
        global $wpdb;

        $optionNamePatterns = [
            $wpdb->esc_like($pluginNamespace . '-') . '%',
            $wpdb->esc_like('_transient_' . $pluginNamespace . '-') . '%',
            $wpdb->esc_like('_transient_timeout_' . $pluginNamespace . '-') . '%',
            $wpdb->esc_like('_site_transient_' . $pluginNamespace . '-') . '%',
            $wpdb->esc_like('_site_transient_timeout_' . $pluginNamespace . '-') . '%',
        ];
        foreach ($optionNamePatterns as $optionNamePattern) {
            $wpdb->query(
                $wpdb->prepare(
                    "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s", // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
                    $optionNamePattern
                )
            );
        }

        if (!is_multisite()) {
            return;
        }

        foreach ($optionNamePatterns as $optionNamePattern) {
            $wpdb->query(
                $wpdb->prepare(
                    "DELETE FROM {$wpdb->sitemeta} WHERE meta_key LIKE %s", // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
                    $optionNamePattern
                )
            );
        }
    }

    /**
     * The custom post types are no longer registered by the time this runs,
     * so the entries are removed directly, together with everything hanging
     * off them, rather than through `wp_delete_post`.
     *
     * @param string[] $customPostTypes
     */
    protected static function deleteCustomPosts(array $customPostTypes): void
    {
        global $wpdb;

        $placeholders = implode(',', array_fill(0, count($customPostTypes), '%s'));
        /** @var string[] */
        $customPostIDs = $wpdb->get_col(
            $wpdb->prepare(
                "SELECT ID FROM {$wpdb->posts} WHERE post_type IN ({$placeholders})", // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
                ...$customPostTypes
            )
        );
        if ($customPostIDs === []) {
            return;
        }

        $customPostIDPlaceholders = implode(',', array_fill(0, count($customPostIDs), '%d'));
        $statements = [
            "DELETE FROM {$wpdb->postmeta} WHERE post_id IN ({$customPostIDPlaceholders})",
            "DELETE FROM {$wpdb->term_relationships} WHERE object_id IN ({$customPostIDPlaceholders})",
            "DELETE FROM {$wpdb->posts} WHERE ID IN ({$customPostIDPlaceholders}) OR post_parent IN ({$customPostIDPlaceholders})",
        ];
        foreach ($statements as $statement) {
            $substitutionCount = substr_count($statement, '%d');
            $substitutions = $substitutionCount === count($customPostIDs)
                ? $customPostIDs
                : array_merge($customPostIDs, $customPostIDs);
            $wpdb->query(
                $wpdb->prepare($statement, ...$substitutions) // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
            );
        }
    }
}
