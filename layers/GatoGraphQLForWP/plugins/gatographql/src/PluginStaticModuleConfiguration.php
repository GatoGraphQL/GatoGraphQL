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
     * Add the "Gato GraphQL PRO" bundle to the Extensions page.
     *
     * @since v2.2
     */
    public static function displayGatoGraphQLPROBundleOnExtensionsPage(): bool
    {
        return false;
    }

    /**
     * Add the "feature" Gato GraphQL PRO bundles to the Extensions page.
     *
     * @since v6.0
     */
    public static function displayGatoGraphQLPROFeatureBundlesOnExtensionsPage(): bool
    {
        return true;
    }

    /**
     * Add the Gato GraphQL PRO bundle containing all the "feature"
     * extension bundles to the Extensions page.
     *
     * @since v6.0
     */
    public static function offerGatoGraphQLPROAllExtensionsBundle(): bool
    {
        return false;
    }

    /**
     * Add the Gato GraphQL PRO extensions to the Extensions page.
     *
     * @since v6.0
     */
    public static function offerGatoGraphQLPROExtensions(): bool
    {
        return false;
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
