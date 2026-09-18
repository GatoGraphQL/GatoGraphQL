<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\PluginSkeleton;

/**
 * A piece of the site the plugin must create before it can work: a custom
 * database table, or anything else which outlives a single request and must
 * be brought up to date when the plugin's code moves on.
 *
 * This is not how setup data is installed. Setup data is content the user
 * may then edit, so it is installed once, keyed to the plugin version that
 * introduced it, and deliberately never touched again
 * {@see AbstractPlugin::getPluginSetupDataVersionCallbacks()}. A feature is
 * the opposite: it must converge on its current definition every time, so it
 * carries a version of its own, and `install()` runs whenever the two differ.
 */
interface FeatureInstallerInterface
{
    /**
     * Unique among all features, from any plugin or extension, as they all
     * share a single entry in the Options table.
     */
    public function getFeatureSlug(): string;

    /**
     * Bumped whenever {@see FeatureInstallerInterface::install()} must run
     * again. It has nothing to do with the plugin's version: a schema
     * settles at its own pace, and releases that leave it alone must not
     * pay for it.
     */
    public function getFeatureVersion(): string;

    /**
     * Create what the feature needs, or bring it up to date. It may run
     * again at any time, on a site at any earlier version, so it must be
     * idempotent, and it must throw rather than fail quietly: the version
     * is recorded only once it returns.
     */
    public function install(): void;

    /**
     * The database tables the feature creates, with the site's table prefix,
     * so that uninstalling drops those and nothing else.
     *
     * They are recorded when the feature installs, by the very code that
     * creates them, so the record cannot drift from what is on the site. They
     * are not matched by name: a plugin's database namespace can be a word as
     * general as "graphql", and dropping every table starting with it would
     * take another plugin's data with it.
     *
     * @return string[]
     */
    public function getTableNames(): array;
}
