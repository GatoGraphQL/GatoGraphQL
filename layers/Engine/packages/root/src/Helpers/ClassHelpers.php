<?php

declare(strict_types=1);

namespace PoP\Root\Helpers;

use GatoGraphQL\GatoGraphQL\PluginApp;

class ClassHelpers
{
    /**
     * The PSR-4 namespace, with format "Vendor\Project".
     *
     * If own classes have been prefixed, then format will be:
     * "GatoGraphQLScoped\Vendor\Project"
     *
     * @see ci/scoping/plugins/gatographql/scoper-internal.inc.php
     */
    public static function getClassPSR4Namespace(string $class): string
    {
        $parts = explode('\\', $class);
        return $parts[0] . (isset($parts[1]) ? '\\' . $parts[1] : '') . ($parts[0] === static::getPluginTopLevelNamespace() && isset($parts[2]) ? '\\' . $parts[2] : '');
    }

    /**
     * If the plugin is prefixed using PHP-Scoper, use the
     * top-level namespace name calculated here.
     *
     * This same name must be input in the scoper-internal.inc.php
     * config file.
     *
     * For instance, plugin "Gato GraphQL" will have the top-level
     * namespace "GatoGraphQLScoped".
     *
     * @see ci/scoping/plugins/gatographql/scoper-internal.inc.php
     */
    public static function getPluginTopLevelNamespace(): string
    {
        $pluginName = PluginApp::getMainPlugin()->getPluginName();
        return str_replace([' ', '-'], '', $pluginName) . 'Scoped';
    }
}
