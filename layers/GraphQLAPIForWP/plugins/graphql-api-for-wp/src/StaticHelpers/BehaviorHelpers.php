<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\StaticHelpers;

use GraphQLAPI\GraphQLAPI\Constants\ResetSettingsOptions;
use GraphQLAPI\GraphQLAPI\Facades\UserSettingsManagerFacade;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\PluginManagementFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\PluginEnvironment;
use PoPSchema\SchemaCommons\Constants\Behaviors;
use PoP\Root\Environment as RootEnvironment;

class BehaviorHelpers
{
    public static function getDefaultBehavior(): string
    {
        return static::areUnsafeDefaultsEnabled()
            ? Behaviors::DENY
            : Behaviors::ALLOW;
    }

    /**
     * Unsafe defaults enabled:
     *
     * - The single endpoint is enabled
     * - The “sensitive” data elements are exposed in the schema
     * - All settings options and meta keys can be queried
     * - The number of entities that can be queried at once is unlimited
     *
     * Unsafe defaults disabled:
     *
     * - The single endpoint is disabled
     * - The “sensitive” data elements (eg: input field `status` to query posts with status `"draft"`) are not added to the schema
     * - Only a few of settings options and meta keys (for posts, users, etc) can be queried
     * - The number of entities (for posts, users, etc) that can be queried at once is limited
     */
    public static function areUnsafeDefaultsEnabled(): bool
    {
        /**
         * If Settings => Reset Settings was submitted
         */
        $userSettingsManager = UserSettingsManagerFacade::getInstance();
        if (
            $userSettingsManager->hasSetting(
                PluginManagementFunctionalityModuleResolver::PLUGIN_MANAGEMENT,
                PluginManagementFunctionalityModuleResolver::OPTION_USE_SAFE_OR_UNSAFE_DEFAULT_BEHAVIOR
            )
        ) {
            return $userSettingsManager->getSetting(
                PluginManagementFunctionalityModuleResolver::PLUGIN_MANAGEMENT,
                PluginManagementFunctionalityModuleResolver::OPTION_USE_SAFE_OR_UNSAFE_DEFAULT_BEHAVIOR
            ) === ResetSettingsOptions::UNSAFE;
        }

        /**
         * If env var `SETTINGS_OPTION_ENABLE_UNSAFE_DEFAULT_BEHAVIOR` is defined
         */
        if (getenv(PluginEnvironment::SETTINGS_OPTION_ENABLE_UNSAFE_DEFAULT_BEHAVIOR) !== false) {
            return (bool)getenv(PluginEnvironment::SETTINGS_OPTION_ENABLE_UNSAFE_DEFAULT_BEHAVIOR);
        }

        /**
         * If wp-config.php constant `GRAPHQL_API_SETTINGS_OPTION_ENABLE_UNSAFE_DEFAULT_BEHAVIOR` is defined
         */
        if (PluginEnvironmentHelpers::isWPConfigConstantDefined(PluginEnvironment::SETTINGS_OPTION_ENABLE_UNSAFE_DEFAULT_BEHAVIOR)) {
            return (bool)PluginEnvironmentHelpers::getWPConfigConstantValue(PluginEnvironment::SETTINGS_OPTION_ENABLE_UNSAFE_DEFAULT_BEHAVIOR);
        }

        /**
         * Base case: If on the DEV or PROD environment
         */
        return RootEnvironment::isApplicationEnvironmentDev();
    }
}
