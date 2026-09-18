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
     * bootstrapped, so it has no services with which to work out the
     * plugin's namespace, its tables or its custom post types. This
     * option is the only thing it must find, and it carries all of them.
     *
     * @see PluginUninstaller
     */
    public final const INSTALLED_DATA = 'installed-data';

    /**
     * The suffix under which {@see PluginOptions::INSTALLED_DATA} is
     * stored, whatever the plugin's namespace. `uninstall.php` searches
     * the Options table for it, and reads the namespace from within.
     */
    public final const INSTALLED_DATA_OPTION_SUFFIX = '-' . self::INSTALLED_DATA;
}
