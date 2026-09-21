<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\PluginSkeleton;

interface PluginInterface
{
    public function setup(): void;

    /**
     * Execute logic after the plugin/extension has just been activated.
     *
     * Notice that this will be executed when first time activated, or
     * reactivated (i.e. activated => deactivated => activated).
     *
     * Then, when installing setup data, we must first check that the entry
     * does not already exist. This will also avoid duplicating setup data
     * when downgrading the plugin to a lower version, and then upgrading
     * again.
     */
    public function pluginJustActivated(): void;

    /**
     * Execute logic after the plugin/extension has just been updated
     */
    public function pluginJustUpdated(string $newVersion, string $previousVersion): void;

    /**
     * Allow to install plugin setup data after
     * a commercial license has been activated
     */
    public function isLicenseJustActivated(): void;

    /**
     * Execute actions when the depended-upon plugin or theme's
     * status has changed (activated/deactivated).
     *
     * @param string[] $pluginFilesOrThemeSlugsWithStatusChange The plugin files or theme slugs with status change
     */
    public function dependedUponPluginOrThemeStatusJustChanged(array $pluginFilesOrThemeSlugsWithStatusChange): void;

    /**
     * Plugin name
     */
    public function getPluginName(): string;

    /**
     * Plugin name
     */
    public function getPluginMenuName(): string;

    /**
     * Plugin base name
     */
    public function getPluginBaseName(): string;

    /**
     * Plugin slug
     */
    public function getPluginSlug(): string;

    /**
     * Plugin main file
     */
    public function getPluginFile(): string;

    /**
     * Dependencies on other plugins, to regenerate the schema
     * when these are activated/deactived
     *
     * @return string[]
     */
    public function getDependentOnPluginFiles(): array;

    /**
     * Get the list of theme slugs that this extension depends on
     *
     * @return string[]
     */
    public function getDependentOnThemeSlugs(): array;

    /**
     * Commit hash when merging PR in repo, injected during the CI run
     * when generating the .zip plugin.
     */
    public function getCommitHash(): ?string;

    /**
     * Plugin version + "#{commit hash}" (if it exists)
     */
    public function getPluginVersionWithCommitHash(): string;

    /**
     * Plugin version
     */
    public function getPluginVersion(): string;

    /**
     * Plugin dir
     */
    public function getPluginDir(): string;

    /**
     * Plugin URL
     */
    public function getPluginURL(): string;

    /**
     * PluginInfo class for the Plugin
     */
    public function getInfo(): ?PluginInfoInterface;

    /**
     * Namespace the plugin.
     *
     * Useful for standalone plugins to override
     * this value, and automatically have entities
     * not conflict with Gato GraphQL (or other
     * standalone plugins).
     */
    public function getPluginNamespace(): string;

    /**
     * Namespace the names of the entity types the plugin registers:
     * custom post types, taxonomies, etc.
     *
     * Useful for standalone plugins to override
     * this value, and automatically have entities
     * not conflict with Gato GraphQL (or other
     * standalone plugins).
     *
     * Use 7 chars to identify it, as CPTs have
     * a max length of 20 chars: this is the one namespace which cannot simply
     * be the plugin's own, since "gatographql-schemaconfig" would already be
     * 24 chars and WordPress would refuse to register it.
     *
     * This is not what names the plugin's options, meta keys or database
     * tables: those all carry the plugin's own namespace
     * {@see PluginInterface::getPluginNamespace()}, which has room for it.
     */
    public function getPluginNamespaceForEntityTypeNames(): string;

    /**
     * Namespace classes. Eg: The container caching class.
     *
     * Useful for standalone plugins to override
     * this value, and automatically have entities
     * not conflict with Gato GraphQL (or other
     * standalone plugins).
     */
    public function getPluginNamespaceForClass(): string;

    /**
     * The features this plugin or extension installs so that it can work,
     * such as a custom database table. Declared here, and not only on the
     * base class, because the main plugin collects them from every extension
     * it has been given, and knows each only through this interface.
     *
     * @return FeatureInstallerInterface[]
     */
    public function getFeatureInstallers(): array;

    public function getPluginWPConfigConstantNamespace(): string;

    public function getPluginWPContentFolderName(): string;

    /**
     * If the plugin is prefixed using PHP-Scoper, use the
     * top-level namespace name calculated here.
     */
    public function getPluginInternalScopingTopLevelNamespace(): string;
}
