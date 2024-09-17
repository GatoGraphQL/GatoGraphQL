<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL;

/**
 * These methods are static as they are referenced from some
 * class before the Service Container is created/initialized,
 * and they do not need to be configurable (whether via an
 * environment variable or hook).
 */
class PluginStaticModuleConfiguration
{
    /**
     * Indicate if, when doing ?endpoint_group=pluginOwnUse, all the
     * schema-type modules must still be enabled (even if they've
     * been disabled).
     */
    public static function alwaysEnableAllSchemaTypeModulesForAdminPluginOwnUseGraphQLEndpoint(): bool
    {
        return false;
    }

    /**
     * Since only releasing the PRO bundle, disable generating
     * all other bundles.
     *
     * @since v2.2
     */
    public static function offerGatoGraphQLPROBundle(): bool
    {
        return true;
    }

    /**
     * Since extracting persisted queries into a standalone plugin,
     * there's no more "plugin setup data" to install, but keep
     * the reference for future use.
     *
     * @since v6.0
     */
    public static function canManageInstallingPluginSetupData(): bool
    {
        return false;
    }
}
