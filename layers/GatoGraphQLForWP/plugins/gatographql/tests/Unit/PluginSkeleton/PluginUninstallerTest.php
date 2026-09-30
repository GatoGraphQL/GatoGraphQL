<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Unit\PluginSkeleton;

use Brain\Monkey;
use GatoGraphQL\GatoGraphQL\PluginSkeleton\PluginUninstaller;
use GatoGraphQL\GatoGraphQL\Settings\InstalledDataSettingsManager;
use PHPUnit\Framework\TestCase;

use function Brain\Monkey\Functions\when;

/**
 * The uninstaller runs with WordPress loaded but no plugin, so it is driven
 * entirely by the record each site keeps and by `$wpdb`. Both are faked here:
 * the record per site, and a `$wpdb` which remembers every statement it was
 * asked to run.
 */
class PluginUninstallerTest extends TestCase
{
    private const NAMESPACE = 'gatographql';
    private const ENTITY_TYPE_NAMESPACE = 'graphql';

    private FakeWPDB $wpdb;

    /**
     * @var array<int,array<string,mixed>> The installed-data record per site
     */
    private array $recordsBySite = [];

    private int $currentSite = 1;

    protected function setUp(): void
    {
        parent::setUp();
        Monkey\setUp();

        $this->wpdb = new FakeWPDB();
        $this->currentSite = 1;
        $this->recordsBySite = [];
        $GLOBALS['wpdb'] = $this->wpdb; // phpcs:ignore SlevomatCodingStandard.Variables.DisallowSuperGlobalVariable

        when('get_option')->alias(fn (string $name): array|false => $this->getCurrentSiteRecord());
        when('wp_cache_flush')->justReturn(true);
        when('is_multisite')->justReturn(false);
    }

    protected function tearDown(): void
    {
        unset($GLOBALS['wpdb']); // phpcs:ignore SlevomatCodingStandard.Variables.DisallowSuperGlobalVariable
        Monkey\tearDown();
        parent::tearDown();
    }

    /**
     * @return array<string,mixed>|false
     */
    private function getCurrentSiteRecord(): array|false
    {
        return $this->recordsBySite[$this->currentSite] ?? false;
    }

    /**
     * @param string[] $tableNames
     * @param string[] $customPostTypes
     * @param string[] $taxonomies
     * @return array<string,mixed>
     */
    private function record(bool $deleteData, bool $deleteContent = false, array $tableNames = [], array $customPostTypes = [], array $taxonomies = []): array
    {
        return [
            InstalledDataSettingsManager::KEY_FEATURES => [
                'some-feature' => [
                    InstalledDataSettingsManager::KEY_VERSION => '1.0.0',
                    InstalledDataSettingsManager::KEY_TABLE_NAMES => $tableNames,
                ],
            ],
            InstalledDataSettingsManager::KEY_UNINSTALL => [
                InstalledDataSettingsManager::KEY_DELETE_DATA => $deleteData,
                InstalledDataSettingsManager::KEY_DELETE_CONTENT => $deleteContent,
                InstalledDataSettingsManager::KEY_CUSTOM_POST_TYPES => $customPostTypes,
                InstalledDataSettingsManager::KEY_TAXONOMIES => $taxonomies,
            ],
        ];
    }

    public function testNothingIsRemovedUnlessTheSiteAskedForIt(): void
    {
        $this->recordsBySite[1] = $this->record(false, true, ['wp_gatographql_table'], ['graphql-query']);

        PluginUninstaller::uninstall(self::NAMESPACE, self::ENTITY_TYPE_NAMESPACE);

        $this->assertSame([], $this->wpdb->queries);
    }

    public function testNothingIsRemovedWithoutARecord(): void
    {
        PluginUninstaller::uninstall(self::NAMESPACE, self::ENTITY_TYPE_NAMESPACE);

        $this->assertSame([], $this->wpdb->queries);
    }

    public function testOnlyTheTablesUnderThePluginsOwnPrefixAreDropped(): void
    {
        $this->recordsBySite[1] = $this->record(true, false, [
            'wp_gatographql_translation_memory',
            'wp_users',
            'wp_otherplugin_table',
            'gatographql_table',
            'wp_gatographql_x; DROP TABLE wp_posts',
        ]);

        PluginUninstaller::uninstall(self::NAMESPACE, self::ENTITY_TYPE_NAMESPACE);

        $dropStatements = array_values(array_filter(
            $this->wpdb->queries,
            static fn (string $query): bool => str_starts_with($query, 'DROP TABLE')
        ));
        $this->assertSame(
            ['DROP TABLE IF EXISTS `wp_gatographql_translation_memory`'],
            $dropStatements
        );
    }

    public function testOptionsAndMetaAreRemovedByNamespaceWithTheWildcardsEscaped(): void
    {
        $this->recordsBySite[1] = $this->record(true);

        PluginUninstaller::uninstall(self::NAMESPACE, self::ENTITY_TYPE_NAMESPACE);

        $this->assertContains("DELETE FROM wp_options WHERE option_name LIKE 'gatographql-%'", $this->wpdb->queries);
        $this->assertContains('DELETE FROM wp_options WHERE option_name LIKE \'\\\\_transient\\\\_gatographql-%\'', $this->wpdb->queries);
        $this->assertContains('DELETE FROM wp_options WHERE option_name LIKE \'\\\\_site\\\\_transient\\\\_timeout\\\\_gatographql-%\'', $this->wpdb->queries);
        foreach (['wp_postmeta', 'wp_termmeta', 'wp_usermeta', 'wp_commentmeta'] as $metaTable) {
            $this->assertContains("DELETE FROM {$metaTable} WHERE meta_key LIKE 'gatographql-%' OR meta_key LIKE '" . '\\\\_gatographql-%' . "'", $this->wpdb->queries);
        }
        $this->assertNotContains('DELETE FROM wp_sitemeta', array_map(static fn (string $query): string => substr($query, 0, 23), $this->wpdb->queries));
    }

    public function testEntriesAreRemovedOnlyWhenAskedFor(): void
    {
        $this->recordsBySite[1] = $this->record(true, false, [], ['graphql-query']);
        $this->wpdb->columns['SELECT ID FROM wp_posts'] = ['10', '11'];

        PluginUninstaller::uninstall(self::NAMESPACE, self::ENTITY_TYPE_NAMESPACE);

        $this->assertSame([], array_filter(
            $this->wpdb->queries,
            static fn (string $query): bool => str_contains($query, 'wp_posts')
        ));
    }

    public function testEntriesGoWithTheirRevisionsAndCommentsAndTheirAttachmentsAreDetached(): void
    {
        $this->recordsBySite[1] = $this->record(true, true, [], ['graphql-query']);
        $this->wpdb->columns['SELECT ID FROM wp_posts WHERE post_type IN'] = ['10', '11'];
        $this->wpdb->columns["SELECT ID FROM wp_posts WHERE post_type = 'revision'"] = ['12'];
        $this->wpdb->columns['SELECT comment_ID FROM wp_comments'] = ['7'];

        PluginUninstaller::uninstall(self::NAMESPACE, self::ENTITY_TYPE_NAMESPACE);

        $this->assertContains('DELETE FROM wp_commentmeta WHERE comment_id IN (7)', $this->wpdb->queries);
        $this->assertContains('DELETE FROM wp_comments WHERE comment_ID IN (7)', $this->wpdb->queries);
        $this->assertContains('DELETE FROM wp_postmeta WHERE post_id IN (10,11,12)', $this->wpdb->queries);
        $this->assertContains('DELETE FROM wp_term_relationships WHERE object_id IN (10,11,12)', $this->wpdb->queries);
        $this->assertContains('DELETE FROM wp_posts WHERE ID IN (10,11,12)', $this->wpdb->queries);
        $this->assertContains("UPDATE wp_posts SET post_parent = 0 WHERE post_type = 'attachment' AND post_parent IN (10,11)", $this->wpdb->queries);
    }

    /**
     * The record is an option, which whoever can write options can tamper
     * with: an entity type not named under the plugin's own namespace is
     * not the plugin's, whatever the record says.
     */
    public function testOnlyTheEntryTypesUnderThePluginsOwnNamespaceAreRemoved(): void
    {
        $this->recordsBySite[1] = $this->record(
            true,
            true,
            [],
            ['post', 'page', 'attachment', 'graphql-query', 'graphqlquery', 'wp_block'],
            ['category', 'nav_menu', 'graphql-endpoint-category', 'graphql-x; DROP TABLE wp_terms']
        );
        $this->wpdb->columns['SELECT ID FROM wp_posts WHERE post_type IN'] = ['10'];
        $this->wpdb->columns['SELECT term_taxonomy_id FROM wp_term_taxonomy'] = ['5'];
        $this->wpdb->columns['SELECT term_id FROM wp_term_taxonomy'] = ['3'];

        PluginUninstaller::uninstall(self::NAMESPACE, self::ENTITY_TYPE_NAMESPACE);

        $this->assertContains("SELECT ID FROM wp_posts WHERE post_type IN ('graphql-query')", $this->wpdb->selects);
        $this->assertContains("SELECT term_taxonomy_id FROM wp_term_taxonomy WHERE taxonomy IN ('graphql-endpoint-category')", $this->wpdb->selects);
        foreach ($this->wpdb->selects as $select) {
            $this->assertStringNotContainsString("'post'", $select);
            $this->assertStringNotContainsString("'category'", $select);
            $this->assertStringNotContainsString('DROP TABLE', $select);
        }
    }

    public function testATamperedRecordNamingOnlyOtherEntryTypesRemovesNoEntries(): void
    {
        $this->recordsBySite[1] = $this->record(true, true, [], ['post', 'page'], ['category']);
        $this->wpdb->columns['SELECT ID FROM wp_posts'] = ['10', '11'];

        PluginUninstaller::uninstall(self::NAMESPACE, self::ENTITY_TYPE_NAMESPACE);

        $this->assertSame([], array_filter(
            [...$this->wpdb->queries, ...$this->wpdb->selects],
            static fn (string $query): bool => str_contains($query, 'wp_posts')
                || str_contains($query, 'wp_terms')
                || str_contains($query, 'wp_term_taxonomy')
                || str_contains($query, 'wp_term_relationships')
        ));
    }

    public function testTheUserOptionsAndTheSiteScopedUserMetaAreSweptUnderTheSitesPrefix(): void
    {
        $this->recordsBySite[1] = $this->record(true);

        PluginUninstaller::uninstall(self::NAMESPACE, self::ENTITY_TYPE_NAMESPACE);

        $this->assertContains(
            'DELETE FROM wp_usermeta WHERE meta_key LIKE \'wp\\\\_gatographql-%\' OR meta_key LIKE \'\\\\_wp\\\\_gatographql-%\'',
            $this->wpdb->queries
        );
    }

    /**
     * The folder is process-wide state (`WP_CONTENT_DIR` is a constant), so
     * the cases share one scratch `wp-content` and each lays out what it
     * needs under it.
     */
    private function createScratchWPContentDir(): string
    {
        $wpContentDir = sys_get_temp_dir() . '/gatographql-uninstaller-test-' . uniqid();
        mkdir($wpContentDir, 0777, true);
        if (!defined('WP_CONTENT_DIR')) {
            define('WP_CONTENT_DIR', $wpContentDir);
        }
        /** @var string */
        $definedWPContentDir = constant('WP_CONTENT_DIR');
        if (!str_starts_with($definedWPContentDir, sys_get_temp_dir())) {
            $this->markTestSkipped('WP_CONTENT_DIR is defined outside the temp folder; not deleting from there');
        }
        return $definedWPContentDir;
    }

    private function removeScratchDir(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }
        foreach (array_diff(scandir($dir) ?: [], ['.', '..']) as $entry) {
            $path = $dir . '/' . $entry;
            if (is_dir($path) && !is_link($path)) {
                $this->removeScratchDir($path);
                continue;
            }
            unlink($path);
        }
        rmdir($dir);
    }

    public function testOnlyTheCacheAndLogsGoFromThePluginsFolderAndAReservedFolderIsNeverTouched(): void
    {
        $wpContentDir = $this->createScratchWPContentDir();
        $pluginFolder = $wpContentDir . '/gatographql';
        mkdir($pluginFolder . '/cache/container', 0777, true);
        mkdir($pluginFolder . '/logs', 0777, true);
        touch($pluginFolder . '/cache/container/services.php');
        touch($pluginFolder . '/logs/errors.log');
        touch($pluginFolder . '/somebody-elses-file.txt');
        mkdir($wpContentDir . '/uploads/cache', 0777, true);
        touch($wpContentDir . '/uploads/cache/image.jpg');
        mkdir($wpContentDir . '/linked-target/cache', 0777, true);
        touch($wpContentDir . '/linked-target/cache/kept.txt');
        symlink($wpContentDir . '/linked-target', $wpContentDir . '/linked');
        $this->recordsBySite[1] = $this->record(true);

        try {
            PluginUninstaller::uninstall(self::NAMESPACE, self::ENTITY_TYPE_NAMESPACE, 'GatoGraphQL');
            $this->assertDirectoryDoesNotExist($pluginFolder . '/cache');
            $this->assertDirectoryDoesNotExist($pluginFolder . '/logs');
            $this->assertFileExists($pluginFolder . '/somebody-elses-file.txt');

            unlink($pluginFolder . '/somebody-elses-file.txt');
            PluginUninstaller::uninstall(self::NAMESPACE, self::ENTITY_TYPE_NAMESPACE, 'gatographql');
            $this->assertDirectoryDoesNotExist($pluginFolder);

            PluginUninstaller::uninstall(self::NAMESPACE, self::ENTITY_TYPE_NAMESPACE, 'uploads');
            $this->assertFileExists($wpContentDir . '/uploads/cache/image.jpg');

            PluginUninstaller::uninstall(self::NAMESPACE, self::ENTITY_TYPE_NAMESPACE, 'linked');
            $this->assertFileExists($wpContentDir . '/linked-target/cache/kept.txt');
        } finally {
            $this->removeScratchDir($wpContentDir);
        }
    }

    public function testEachSiteOfANetworkIsHandledByItsOwnRecord(): void
    {
        when('is_multisite')->justReturn(true);
        when('get_sites')->justReturn([1, 2, 3]);
        when('switch_to_blog')->alias(function (int $siteID): bool {
            $this->currentSite = $siteID;
            $this->wpdb->prefix = $siteID === 1 ? 'wp_' : "wp_{$siteID}_";
            return true;
        });
        when('restore_current_blog')->alias(function (): bool {
            $this->currentSite = 1;
            $this->wpdb->prefix = 'wp_';
            return true;
        });

        $this->recordsBySite[1] = $this->record(true, false, ['wp_gatographql_tm']);
        $this->recordsBySite[2] = $this->record(false, false, ['wp_2_gatographql_tm']);
        $this->recordsBySite[3] = $this->record(true, false, ['wp_3_gatographql_tm', 'wp_gatographql_tm']);

        PluginUninstaller::uninstall(self::NAMESPACE, self::ENTITY_TYPE_NAMESPACE);

        $dropStatements = array_values(array_filter(
            $this->wpdb->queries,
            static fn (string $query): bool => str_starts_with($query, 'DROP TABLE')
        ));
        $this->assertSame(
            [
                'DROP TABLE IF EXISTS `wp_gatographql_tm`',
                'DROP TABLE IF EXISTS `wp_3_gatographql_tm`',
            ],
            $dropStatements
        );
        $this->assertContains("DELETE FROM wp_options WHERE option_name LIKE 'gatographql-%'", $this->wpdb->queries);
        $this->assertNotContains("DELETE FROM wp_2_options WHERE option_name LIKE 'gatographql-%'", $this->wpdb->queries);
        $this->assertContains("DELETE FROM wp_3_options WHERE option_name LIKE 'gatographql-%'", $this->wpdb->queries);

        $siteMetaStatements = array_values(array_filter(
            $this->wpdb->queries,
            static fn (string $query): bool => str_starts_with($query, 'DELETE FROM wp_sitemeta')
        ));
        $this->assertCount(5, $siteMetaStatements);
    }

    public function testANetworkWhereNoSiteAskedForItIsLeftAlone(): void
    {
        when('is_multisite')->justReturn(true);
        when('get_sites')->justReturn([1, 2]);
        when('switch_to_blog')->alias(function (int $siteID): bool {
            $this->currentSite = $siteID;
            return true;
        });
        when('restore_current_blog')->justReturn(true);

        $this->recordsBySite[1] = $this->record(false, false, ['wp_gatographql_tm']);
        $this->recordsBySite[2] = $this->record(false, false, ['wp_2_gatographql_tm']);

        PluginUninstaller::uninstall(self::NAMESPACE, self::ENTITY_TYPE_NAMESPACE);

        $this->assertSame([], $this->wpdb->queries);
    }
}
