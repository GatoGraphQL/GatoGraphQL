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
     * Plugin name
     */
    public function getPluginName(): string;

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
     * Namespace the entities to store in DB:
     * CPT, taxonomies, etc.
     *
     * Useful for standalone plugins to override
     * this value, and automatically have entities
     * not conflict with Gato GraphQL (or other
     * standalone plugins).
     *
     * Use 7 chars to identify it, as CPTs have
     * a max length of 20 chars.
     */
    public function getPluginNamespaceForDB(): string;

    /**
     * Namespace classes. Eg: The container caching class.
     *
     * Useful for standalone plugins to override
     * this value, and automatically have entities
     * not conflict with Gato GraphQL (or other
     * standalone plugins).
     */
    public function getPluginNamespaceForClass(): string;

    public function getPluginWPConfigConstantNamespace(): string;

    public function getPluginWPContentFolderName(): string;

    /**
     * If the plugin is prefixed using PHP-Scoper, use the
     * top-level namespace name calculated here.
     */
    public function getPluginInternalScopingTopLevelNamespace(): string;
}
