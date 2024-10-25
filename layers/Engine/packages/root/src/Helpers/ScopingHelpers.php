<?php

declare(strict_types=1);

namespace PoP\Root\Helpers;

use PoP\Root\Constants\Scoping;

/**
 * The difference between "External" and "Internal" prefix is:
 *
 *   - External: Scope 3rd-party dependencies (eg: Symfony packages)
 *   - Internal: Scope own dependencies (eg: all packages composing
 *               the Gato GraphQL plugin)
 *
 * "External" is your typical scoping, needed to make sure that,
 * if there's any other (WordPress) plugin installed in the same
 * site with the same dependencies, these do not conflict.
 *
 * "Internal" is used within the Gato GraphQL project to also
 * produce "standalone" plugins, which are plugins wrapping
 * the Gato GraphQL plugin, and adding extra logic, such as
 * "Gato Multilingual for Polylang".
 *
 * Internal scopes this project's own classes, so that, if the
 * same site has 2 or more (WordPress) plugins from this project
 * (eg: "Gato GraphQL" and "Gato Multilingual for WordPress"),
 * these do not conflict with each other.
 */
class ScopingHelpers
{
    /**
     * If the plugin is prefixed using PHP-Scoper, use the
     * top-level namespace name calculated here.
     *
     * This same name must be input in the scoper-internal.inc.php
     * config file.
     *
     * For instance, plugin "Gato GraphQL" will have the top-level
     * namespace "GatoInternalPrefixByGatoGraphQL".
     *
     * @see ci/scoping/plugins/gatographql/scoper-internal.inc.php
     */
    public static function getPluginInternalScopingTopLevelNamespace(string $pluginName): string
    {
        return Scoping::INTERNAL_SCOPING_NAMESPACE_PREFIX . static::convertPluginNameToClassNamespace($pluginName);
    }

    protected static function convertPluginNameToClassNamespace(string $pluginName): string
    {
        return str_replace([' ', '-', ',', '_', '&'], '', $pluginName);
    }

    public static function isNamespaceInternallyScoped(string $class): bool
    {
        return str_starts_with($class, Scoping::INTERNAL_SCOPING_NAMESPACE_PREFIX);
    }

    public static function getPluginExternalScopingTopLevelNamespace(string $pluginName): string
    {
        return Scoping::EXTERNAL_SCOPING_NAMESPACE_PREFIX . static::convertPluginNameToClassNamespace($pluginName);
    }

    public static function isNamespaceExternallyScoped(string $class): bool
    {
        return str_starts_with($class, Scoping::EXTERNAL_SCOPING_NAMESPACE_PREFIX);
    }
}
