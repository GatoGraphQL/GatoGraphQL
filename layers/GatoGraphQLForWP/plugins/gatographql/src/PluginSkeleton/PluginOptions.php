<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\PluginSkeleton;

/**
 * Plugin Option names.
 *
 * They must be namespaced (via the OptionNamespacer service),
 * so that they all start with "gatographql-"
 */
class PluginOptions
{
    /**
     * Store the plugin/extension versions in the Options table,
     * to track when each of them is installed/updated.
     *
     * It is stored as an array of "plugin/extension baseName" => "version"
     */
    public final const PLUGIN_VERSIONS = 'plugin-versions';
    /**
     * Store what the plugin has installed on the site, and everything
     * needed to remove it again.
     *
     * `uninstall.php` runs with WordPress loaded but the plugin not
     * bootstrapped, so it has no services with which to work out which
     * tables and custom post types the plugin installed. It knows its own
     * namespace, which is a compile-time constant, and this option, which
     * that namespace names, carries everything else.
     *
     * @see PluginUninstaller
     */
    public final const INSTALLED_DATA = 'installed-data';
}
