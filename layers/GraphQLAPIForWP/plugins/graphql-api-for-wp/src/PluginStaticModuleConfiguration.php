<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI;

/**
 * These methods are static as they are referenced from some
 * class before the Service Container is created, and they
 * do not need to be configurable via either env var or hook.
 */
class PluginStaticModuleConfiguration
{
    /**
     * Indicate if, when doing ?endpoint_group=pluginOwnUse, all the
     * schema-type modules must still be enabled (even if they've
     * been disabled).
     *
     * @see layers/GraphQLAPIForWP/plugins/graphql-api-for-wp/src/ConfigurationCache/AbstractCacheConfigurationManager.php
     */
    public static function alwaysEnableAllSchemaTypeModulesForAdminPluginOwnUseGraphQLEndpoint(): bool
    {
        return false;
    }
}
