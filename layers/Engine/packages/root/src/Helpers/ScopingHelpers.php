<?php

declare(strict_types=1);

namespace PoP\Root\Helpers;

use PoP\Root\Constants\Scoping;

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
     * namespace "InternallyPrefixedByGatoGraphQL".
     *
     * @see ci/scoping/plugins/gatographql/scoper-internal.inc.php
     */
    public static function getPluginInternalScopingTopLevelNamespace(string $pluginName): string
    {
        return Scoping::INTERNAL_SCOPING_NAMESPACE_PREFIX . str_replace([' ', '-', ',', '_', '&'], '', $pluginName);
    }

    /**
     * If own classes have been prefixed, then the top-level
     * domain will start with "InternallyPrefixed".
     */
    public static function isNamespaceInternallyScoped(string $class): bool
    {
        return str_starts_with($class, Scoping::INTERNAL_SCOPING_NAMESPACE_PREFIX);
    }
}
