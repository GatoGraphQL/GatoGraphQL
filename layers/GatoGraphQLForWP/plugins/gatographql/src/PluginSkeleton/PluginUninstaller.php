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
 * plugin not bootstrapped: there is no container and no service with which to
 * work out which data is the plugin's. It is told the plugin's namespace, and
 * reads the single option the plugin records it all under
 * {@see PluginOptions::INSTALLED_DATA}.
 *
 * The namespace is passed in, and not searched for, because a site may well
 * have Gato GraphQL and a standalone plugin installed side by side: each
 * records an entry of its own, and deleting one must not read the other's and
 * take its data with it.
 *
 * Options and meta keys are namespaced already, by the option and meta
 * namespacers, so they are removed by matching that namespace rather than by
 * keeping a list of what each feature created: a feature added later is
 * removed too, without having to remember to register it here. Tables are
 * the exception, and are dropped by the names the feature recorded when it
 * installed them, so that a table belonging to another plugin can never
 * match.
 */
class PluginUninstaller
{
    /**
     * Remove the plugin's data from every site it was installed on, if the
     * user asked for that. Called from `uninstall.php`, which passes the
     * namespace of the plugin being deleted.
     */
    public static function uninstall(string $pluginNamespace): void
    {
        if ($pluginNamespace === '') {
            return;
        }

        $installedData = get_option(PluginDataNaming::namespaceOptionName($pluginNamespace, PluginOptions::INSTALLED_DATA));
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

        $tableNames = [];
        $features = $installedData[InstalledDataSettingsManager::KEY_FEATURES] ?? [];
        if (is_array($features)) {
            foreach ($features as $feature) {
                if (!is_array($feature) || !is_array($feature[InstalledDataSettingsManager::KEY_TABLE_NAMES] ?? null)) {
                    continue;
                }
                foreach ($feature[InstalledDataSettingsManager::KEY_TABLE_NAMES] as $tableName) {
                    $tableNames[] = (string) $tableName;
                }
            }
        }
        $tableNames = array_values(array_unique($tableNames));

        $deleteContent = (bool) ($uninstallData[InstalledDataSettingsManager::KEY_DELETE_CONTENT] ?? false);
        $customPostTypes = $uninstallData[InstalledDataSettingsManager::KEY_CUSTOM_POST_TYPES] ?? [];
        if (!is_array($customPostTypes)) {
            $customPostTypes = [];
        }
        /** @var string[] $customPostTypes */

        $taxonomies = $uninstallData[InstalledDataSettingsManager::KEY_TAXONOMIES] ?? [];
        if (!is_array($taxonomies)) {
            $taxonomies = [];
        }
        /** @var string[] $taxonomies */

        /**
         * Options, meta and tables are all per-site, and WordPress runs
         * `uninstall.php` only for the site the plugin is being deleted
         * from, so a network-wide delete would otherwise leave every other
         * site's data behind.
         */
        if (!is_multisite()) {
            self::uninstallFromCurrentSite($pluginNamespace, $tableNames, $customPostTypes, $taxonomies, $deleteContent);
            return;
        }

        /** @var int[] */
        $sites = get_sites(['fields' => 'ids', 'number' => 0]);
        foreach ($sites as $siteID) {
            switch_to_blog($siteID);
            self::uninstallFromCurrentSite($pluginNamespace, $tableNames, $customPostTypes, $taxonomies, $deleteContent);
            restore_current_blog();
        }
    }

    /**
     * @param string[] $tableNames
     * @param string[] $customPostTypes
     * @param string[] $taxonomies
     */
    protected static function uninstallFromCurrentSite(
        string $pluginNamespace,
        array $tableNames,
        array $customPostTypes,
        array $taxonomies,
        bool $deleteContent,
    ): void {
        if ($deleteContent && $customPostTypes !== []) {
            self::deleteCustomPosts($customPostTypes);
        }
        if ($deleteContent && $taxonomies !== []) {
            self::deleteTaxonomyTerms($taxonomies);
        }
        self::dropTables($tableNames);
        self::deleteMeta($pluginNamespace);
        self::deleteOptions($pluginNamespace);
    }

    /**
     * Only the tables each feature recorded as it created them are dropped.
     *
     * @param string[] $tableNames
     */
    protected static function dropTables(array $tableNames): void
    {
        global $wpdb;

        foreach ($tableNames as $tableName) {
            if (preg_match('/^[A-Za-z0-9_]+$/', $tableName) !== 1) {
                continue;
            }
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
        $metaKeyPattern = $wpdb->esc_like(PluginDataNaming::getMetaKeyPrefix($pluginNamespace, false)) . '%';
        $underscoredMetaKeyPattern = $wpdb->esc_like(PluginDataNaming::getMetaKeyPrefix($pluginNamespace)) . '%';
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
     * The names come from {@see PluginDataNaming}, which is also what the
     * namespacer service used to write them, so the two cannot drift apart.
     * That covers the transients too: those are rows in the Options table
     * under prefixes of WordPress's own, on top of the plugin's.
     *
     * This removes the plugin's record of its own installed data too, which
     * is wanted: the plugin is going away, and the entry is of no use to
     * anything that remains.
     */
    protected static function deleteOptions(string $pluginNamespace): void
    {
        global $wpdb;

        $optionNamePatterns = array_map(
            static fn (string $optionNamePrefix): string => $wpdb->esc_like($optionNamePrefix) . '%',
            PluginDataNaming::getOptionNamePrefixes($pluginNamespace)
        );
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
     * The terms of the plugin's own taxonomies, which outlive the entries
     * they were attached to: deleting an entry unlinks it from its terms,
     * and leaves the terms themselves in place for a taxonomy nothing will
     * register again.
     *
     * This is the counterpart of what {@see deleteCustomPosts()} removes,
     * and neither covers the other: that one unlinks the plugin's entries
     * from every taxonomy, including WordPress's own, while this one unlinks
     * the plugin's taxonomies from every entry, including other plugins'.
     *
     * @param string[] $taxonomies
     */
    protected static function deleteTaxonomyTerms(array $taxonomies): void
    {
        global $wpdb;

        $placeholders = implode(',', array_fill(0, count($taxonomies), '%s'));
        /** @var string[] */
        $termTaxonomyIDs = $wpdb->get_col(
            $wpdb->prepare(
                "SELECT term_taxonomy_id FROM {$wpdb->term_taxonomy} WHERE taxonomy IN ({$placeholders})", // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
                ...$taxonomies
            )
        );
        if ($termTaxonomyIDs === []) {
            return;
        }
        /** @var string[] */
        $termIDs = $wpdb->get_col(
            $wpdb->prepare(
                "SELECT term_id FROM {$wpdb->term_taxonomy} WHERE taxonomy IN ({$placeholders})", // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
                ...$taxonomies
            )
        );

        $termTaxonomyIDPlaceholders = implode(',', array_fill(0, count($termTaxonomyIDs), '%d'));
        foreach (
            [
                "DELETE FROM {$wpdb->term_relationships} WHERE term_taxonomy_id IN ({$termTaxonomyIDPlaceholders})",
                "DELETE FROM {$wpdb->term_taxonomy} WHERE term_taxonomy_id IN ({$termTaxonomyIDPlaceholders})",
            ] as $statement
        ) {
            $wpdb->query(
                $wpdb->prepare($statement, ...$termTaxonomyIDs) // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
            );
        }

        /**
         * A term row can be shared by more than one taxonomy, so only the
         * terms which no taxonomy claims any more are removed.
         */
        $termIDPlaceholders = implode(',', array_fill(0, count($termIDs), '%d'));
        /** @var string[] */
        $unclaimedTermIDs = $wpdb->get_col(
            $wpdb->prepare(
                "SELECT t.term_id FROM {$wpdb->terms} t
                    LEFT JOIN {$wpdb->term_taxonomy} tt ON tt.term_id = t.term_id
                    WHERE t.term_id IN ({$termIDPlaceholders}) AND tt.term_taxonomy_id IS NULL", // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
                ...$termIDs
            )
        );
        if ($unclaimedTermIDs === []) {
            return;
        }

        $unclaimedTermIDPlaceholders = implode(',', array_fill(0, count($unclaimedTermIDs), '%d'));
        foreach (
            [
                "DELETE FROM {$wpdb->termmeta} WHERE term_id IN ({$unclaimedTermIDPlaceholders})",
                "DELETE FROM {$wpdb->terms} WHERE term_id IN ({$unclaimedTermIDPlaceholders})",
            ] as $statement
        ) {
            $wpdb->query(
                $wpdb->prepare($statement, ...$unclaimedTermIDs) // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
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
