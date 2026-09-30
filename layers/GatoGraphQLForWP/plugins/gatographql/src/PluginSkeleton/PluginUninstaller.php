<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\PluginSkeleton;

use GatoGraphQL\GatoGraphQL\Settings\InstalledDataSettingsManager;

use function get_option;
use function get_sites;
use function is_multisite;
use function restore_current_blog;
use function switch_to_blog;
use function wp_cache_flush;

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
 * removed too, without having to remember to register it here. Tables,
 * custom post types and taxonomies are the exception, and are removed by
 * the names the plugin recorded, and only when those names carry the
 * plugin's own prefix (the site's table prefix and the plugin's namespace
 * for a table, the plugin's entity-type namespace for the others), so that
 * a table or an entry belonging to WordPress or to another plugin can never
 * match, not even through a record someone has tampered with.
 *
 * On a network, every site holds a record of its own, with its own tables
 * (their names carry the site's table prefix) and its own answer to whether
 * the data is to be deleted, so each site is read and handled by itself.
 */
class PluginUninstaller
{
    /**
     * The folders under `wp-content` that are WordPress's own, or in such
     * common use that the plugin's must never be taken for one of them.
     */
    protected const RESERVED_WP_CONTENT_FOLDER_NAMES = [
        'cache',
        'languages',
        'logs',
        'mu-plugins',
        'plugins',
        'themes',
        'upgrade',
        'upgrade-temp-backup',
        'uploads',
    ];

    /**
     * What the plugin writes under its folder in `wp-content`
     * {@see \GatoGraphQL\GatoGraphQL\PluginEnvironment::getCacheDir()}
     * {@see \GatoGraphQL\GatoGraphQL\PluginEnvironment::getLogsDir()}.
     */
    protected const WP_CONTENT_SUBFOLDER_NAMES = [
        'cache',
        'logs',
    ];

    /**
     * Remove the plugin's data from every site it was installed on, if the
     * user asked for that. Called from `uninstall.php`, which passes the
     * namespace of the plugin being deleted, the namespace its custom post
     * types and taxonomies are named under, and the name of the folder the
     * plugin keeps its cache and logs under in `wp-content`.
     */
    public static function uninstall(string $pluginNamespace, string $entityTypeNamespace, ?string $wpContentFolderName = null): void
    {
        if ($pluginNamespace === '' || $entityTypeNamespace === '') {
            return;
        }

        if (!is_multisite()) {
            $deleted = self::uninstallFromCurrentSite($pluginNamespace, $entityTypeNamespace);
            if ($deleted) {
                self::deleteWPContentFolder($wpContentFolderName);
            }
            return;
        }

        /**
         * Options, meta and tables are all per-site, and WordPress runs
         * `uninstall.php` only for the site the plugin is being deleted
         * from, so a network-wide delete would otherwise leave every other
         * site's data behind.
         */
        $deletedFromAnySite = false;
        /** @var int[] */
        $sites = get_sites(['fields' => 'ids', 'number' => 0]);
        foreach ($sites as $siteID) {
            switch_to_blog($siteID);
            $deleted = self::uninstallFromCurrentSite($pluginNamespace, $entityTypeNamespace);
            restore_current_blog();
            $deletedFromAnySite = $deletedFromAnySite || $deleted;
        }
        if (!$deletedFromAnySite) {
            return;
        }
        self::deleteSiteMeta($pluginNamespace);
        self::deleteWPContentFolder($wpContentFolderName);
        wp_cache_flush();
    }

    /**
     * Read the site's own record, and remove what it says, if the site
     * asked for it.
     *
     * @return bool Whether anything was removed from the site
     */
    protected static function uninstallFromCurrentSite(string $pluginNamespace, string $entityTypeNamespace): bool
    {
        $installedData = get_option(PluginDataNaming::namespaceOptionName($pluginNamespace, PluginOptions::INSTALLED_DATA));
        if (!is_array($installedData)) {
            return false;
        }

        $uninstallData = $installedData[InstalledDataSettingsManager::KEY_UNINSTALL] ?? null;
        if (!is_array($uninstallData)) {
            return false;
        }

        if (!($uninstallData[InstalledDataSettingsManager::KEY_DELETE_DATA] ?? false)) {
            return false;
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
        $customPostTypes = self::getOwnEntityTypeNames(
            $entityTypeNamespace,
            $uninstallData[InstalledDataSettingsManager::KEY_CUSTOM_POST_TYPES] ?? []
        );
        $taxonomies = self::getOwnEntityTypeNames(
            $entityTypeNamespace,
            $uninstallData[InstalledDataSettingsManager::KEY_TAXONOMIES] ?? []
        );

        if ($deleteContent && $customPostTypes !== []) {
            self::deleteCustomPosts($customPostTypes);
        }
        if ($deleteContent && $taxonomies !== []) {
            self::deleteTaxonomyTerms($taxonomies);
        }
        self::dropTables($pluginNamespace, $tableNames);
        self::deleteMeta($pluginNamespace);
        self::deleteUserOptions($pluginNamespace);
        self::deleteOptions($pluginNamespace);

        /**
         * The rows were removed with SQL, which the object cache knows
         * nothing about: with a persistent cache, reinstalling the plugin
         * would otherwise read back the record just deleted, and take the
         * table it names to still exist.
         */
        wp_cache_flush();
        return true;
    }

    /**
     * The custom post types and taxonomies recorded are trusted no further
     * than the tables are: they come from an option, and an option can be
     * written by whoever administers the site. Every entity type the plugin
     * registers is named under its entity-type namespace
     * {@see \GatoGraphQL\GatoGraphQL\Services\CustomPostTypes\AbstractCustomPostType::getCustomPostType()}
     * {@see \GatoGraphQL\GatoGraphQL\Services\Taxonomies\AbstractTaxonomy::getTaxonomy()},
     * so a recorded name that is not, such as `post` or `category`, is not
     * the plugin's, and is not acted on.
     *
     * @return string[]
     */
    protected static function getOwnEntityTypeNames(string $entityTypeNamespace, mixed $entityTypeNames): array
    {
        if (!is_array($entityTypeNames)) {
            return [];
        }
        $entityTypeNamePrefix = $entityTypeNamespace . PluginDataNaming::NAMESPACE_SEPARATOR;
        $ownEntityTypeNames = [];
        foreach ($entityTypeNames as $entityTypeName) {
            if (!is_string($entityTypeName)) {
                continue;
            }
            if (preg_match('/^[a-z0-9_-]+$/', $entityTypeName) !== 1) {
                continue;
            }
            if (!str_starts_with($entityTypeName, $entityTypeNamePrefix)) {
                continue;
            }
            $ownEntityTypeNames[] = $entityTypeName;
        }
        return array_values(array_unique($ownEntityTypeNames));
    }

    /**
     * Only the tables each feature recorded as it created them are dropped,
     * and among those only the ones named the way the plugin names its
     * tables, under the current site's table prefix: the record is an
     * option, and an option can be written by whoever administers the site,
     * which on a network is not who administers the network.
     *
     * @param string[] $tableNames
     */
    protected static function dropTables(string $pluginNamespace, array $tableNames): void
    {
        global $wpdb;

        $tableNamePrefix = PluginDataNaming::getTableNamePrefix($pluginNamespace);
        foreach ($tableNames as $tableName) {
            if (preg_match('/^[A-Za-z0-9_]+$/', $tableName) !== 1) {
                continue;
            }
            if (!str_starts_with($tableName, $tableNamePrefix)) {
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
     * A user option is a row of user meta which WordPress stores under the
     * site's table prefix, so it is neither an option nor a meta key of
     * the plugin's shape, though it is named the way the options are past
     * that prefix. Swept per site, as the prefix is the site's.
     *
     * A plugin's private user meta is scoped to the site the same way, as
     * `wp_usermeta` is one table for the whole network, with the underscore
     * kept in front of the prefix so that the row stays hidden from the
     * profile screen and the REST API. That leaves it matching neither the
     * meta sweep nor the user options, so it is swept here with them.
     */
    protected static function deleteUserOptions(string $pluginNamespace): void
    {
        global $wpdb;

        $userOptionNamePrefix = $wpdb->get_blog_prefix() . PluginDataNaming::getOptionNamePrefix($pluginNamespace);
        $wpdb->query(
            $wpdb->prepare(
                "DELETE FROM {$wpdb->usermeta} WHERE meta_key LIKE %s OR meta_key LIKE %s", // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
                $wpdb->esc_like($userOptionNamePrefix) . '%',
                $wpdb->esc_like(PluginDataNaming::PRIVATE_META_KEY_PREFIX . $userOptionNamePrefix) . '%'
            )
        );
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

        foreach (self::getOptionNamePatterns($pluginNamespace) as $optionNamePattern) {
            $wpdb->query(
                $wpdb->prepare(
                    "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s", // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
                    $optionNamePattern
                )
            );
        }
    }

    /**
     * The network's own options table, which is one for all sites, so it is
     * swept once rather than once per site.
     */
    protected static function deleteSiteMeta(string $pluginNamespace): void
    {
        global $wpdb;

        foreach (self::getOptionNamePatterns($pluginNamespace) as $optionNamePattern) {
            $wpdb->query(
                $wpdb->prepare(
                    "DELETE FROM {$wpdb->sitemeta} WHERE meta_key LIKE %s", // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
                    $optionNamePattern
                )
            );
        }
    }

    /**
     * @return string[]
     */
    protected static function getOptionNamePatterns(string $pluginNamespace): array
    {
        global $wpdb;

        return array_map(
            static fn (string $optionNamePrefix): string => $wpdb->esc_like($optionNamePrefix) . '%',
            PluginDataNaming::getOptionNamePrefixes($pluginNamespace)
        );
    }

    /**
     * The folder under `wp-content` where the plugin keeps its container
     * cache and its logs, which is the one place it writes outside the
     * database {@see \GatoGraphQL\GatoGraphQL\PluginEnvironment::getCacheDir()}.
     * A site which moved that folder elsewhere through the corresponding
     * constant or environment variable keeps it: only the default location
     * is known here.
     */
    protected static function deleteWPContentFolder(?string $wpContentFolderName): void
    {
        if ($wpContentFolderName === null || $wpContentFolderName === '') {
            return;
        }
        /**
         * Lowercased, as the plugin names the folder
         * {@see AbstractPlugin::getPluginWPContentFolderName()}; and never a
         * folder of WordPress's own, whatever the plugin's folder is called.
         */
        $wpContentFolderName = strtolower($wpContentFolderName);
        if (preg_match('/^[a-z0-9_-]+$/', $wpContentFolderName) !== 1) {
            return;
        }
        if (in_array($wpContentFolderName, self::RESERVED_WP_CONTENT_FOLDER_NAMES, true)) {
            return;
        }
        $folder = constant('WP_CONTENT_DIR') . DIRECTORY_SEPARATOR . $wpContentFolderName;
        if (!is_dir($folder) || is_link($folder)) {
            return;
        }
        /**
         * Only what the plugin wrote there goes, and the folder itself only
         * once it is empty: were anything else kept in it, it stays.
         */
        foreach (self::WP_CONTENT_SUBFOLDER_NAMES as $subfolderName) {
            $subfolder = $folder . DIRECTORY_SEPARATOR . $subfolderName;
            if (!is_dir($subfolder) || is_link($subfolder)) {
                continue;
            }
            self::deleteFolder($subfolder);
        }
        $entries = scandir($folder);
        if ($entries === false || array_diff($entries, ['.', '..']) !== []) {
            return;
        }
        rmdir($folder);
    }

    /**
     * A folder reached through a link is not entered: what it links to is
     * not the plugin's to delete. The link itself is removed like a file.
     */
    protected static function deleteFolder(string $folder): void
    {
        $entries = scandir($folder);
        if ($entries === false) {
            return;
        }
        foreach ($entries as $entry) {
            if ($entry === '.' || $entry === '..') {
                continue;
            }
            $path = $folder . DIRECTORY_SEPARATOR . $entry;
            if (is_dir($path) && !is_link($path)) {
                self::deleteFolder($path);
                continue;
            }
            unlink($path);
        }
        rmdir($folder);
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
     * off them, rather than through `wp_delete_post`. What that function
     * does is followed: the revisions go with their entry, meta and all,
     * the comments too, and an attachment whose parent was an entry is kept
     * and detached, as the file it stands for stays in the uploads folder.
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
        /** @var string[] */
        $revisionIDs = $wpdb->get_col(
            $wpdb->prepare(
                "SELECT ID FROM {$wpdb->posts} WHERE post_type = 'revision' AND post_parent IN ({$customPostIDPlaceholders})", // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
                ...$customPostIDs
            )
        );
        $postIDs = array_values(array_unique(array_merge($customPostIDs, $revisionIDs)));
        $postIDPlaceholders = implode(',', array_fill(0, count($postIDs), '%d'));

        /** @var string[] */
        $commentIDs = $wpdb->get_col(
            $wpdb->prepare(
                "SELECT comment_ID FROM {$wpdb->comments} WHERE comment_post_ID IN ({$postIDPlaceholders})", // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
                ...$postIDs
            )
        );
        if ($commentIDs !== []) {
            $commentIDPlaceholders = implode(',', array_fill(0, count($commentIDs), '%d'));
            foreach (
                [
                    "DELETE FROM {$wpdb->commentmeta} WHERE comment_id IN ({$commentIDPlaceholders})",
                    "DELETE FROM {$wpdb->comments} WHERE comment_ID IN ({$commentIDPlaceholders})",
                ] as $statement
            ) {
                $wpdb->query(
                    $wpdb->prepare($statement, ...$commentIDs) // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
                );
            }
        }

        foreach (
            [
                "DELETE FROM {$wpdb->postmeta} WHERE post_id IN ({$postIDPlaceholders})",
                "DELETE FROM {$wpdb->term_relationships} WHERE object_id IN ({$postIDPlaceholders})",
                "DELETE FROM {$wpdb->posts} WHERE ID IN ({$postIDPlaceholders})",
            ] as $statement
        ) {
            $wpdb->query(
                $wpdb->prepare($statement, ...$postIDs) // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
            );
        }

        $wpdb->query(
            $wpdb->prepare(
                "UPDATE {$wpdb->posts} SET post_parent = 0 WHERE post_type = 'attachment' AND post_parent IN ({$customPostIDPlaceholders})", // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
                ...$customPostIDs
            )
        );
    }
}
