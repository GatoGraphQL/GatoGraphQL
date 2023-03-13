<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\StaticHelpers;

use GraphQLAPI\GraphQLAPI\Constants\ResetSettingsOptions;
use GraphQLAPI\GraphQLAPI\Facades\UserSettingsManagerFacade;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\PluginManagementFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\PluginEnvironment;
use GraphQLAPI\GraphQLAPI\PluginManagement\PluginOptionsFormHandler;
use PoPSchema\SchemaCommons\Constants\Behaviors;
use PoP\Root\Environment as RootEnvironment;

class BehaviorHelpers
{
    private static ?bool $areUnsafeDefaultsEnabled = null;

    public static function getDefaultBehavior(): string
    {
        return static::areUnsafeDefaultsEnabled()
            ? Behaviors::DENY
            : Behaviors::ALLOW;
    }

    public static function areUnsafeDefaultsEnabled(): bool
    {
        if (self::$areUnsafeDefaultsEnabled !== null) {
            return self::$areUnsafeDefaultsEnabled;
        }

        $pluginOptionsFormHandler = new PluginOptionsFormHandler();
        $userSettingsManager = UserSettingsManagerFacade::getInstance();

        /**
         * If Settings => Reset Settings was just submitted
         * (i.e. we are in options.php)
         */
        $useSafeOrUnsafeDefaultBehavior = $pluginOptionsFormHandler->maybeOverrideValueFromForm(
            null,
            PluginManagementFunctionalityModuleResolver::RESET_SETTINGS,
            PluginManagementFunctionalityModuleResolver::OPTION_USE_SAFE_OR_UNSAFE_DEFAULT_BEHAVIOR
        );
        if ($useSafeOrUnsafeDefaultBehavior !== null) {
            self::$areUnsafeDefaultsEnabled = $useSafeOrUnsafeDefaultBehavior === ResetSettingsOptions::UNSAFE;
        } elseif (
            $userSettingsManager->hasSetting(
                PluginManagementFunctionalityModuleResolver::RESET_SETTINGS,
                PluginManagementFunctionalityModuleResolver::OPTION_USE_SAFE_OR_UNSAFE_DEFAULT_BEHAVIOR
            )
        ) {
            /**
             * Take stored value of Settings => Reset Settings on DB
             */
            $useSafeOrUnsafeDefaultBehavior = $userSettingsManager->getSetting(
                PluginManagementFunctionalityModuleResolver::RESET_SETTINGS,
                PluginManagementFunctionalityModuleResolver::OPTION_USE_SAFE_OR_UNSAFE_DEFAULT_BEHAVIOR
            );
            self::$areUnsafeDefaultsEnabled = $useSafeOrUnsafeDefaultBehavior === ResetSettingsOptions::UNSAFE;
        } elseif (getenv(PluginEnvironment::SETTINGS_OPTION_ENABLE_UNSAFE_DEFAULT_BEHAVIOR) !== false) {
            /**
             * If env var `SETTINGS_OPTION_ENABLE_UNSAFE_DEFAULT_BEHAVIOR` is defined
             */
            self::$areUnsafeDefaultsEnabled = (bool)getenv(PluginEnvironment::SETTINGS_OPTION_ENABLE_UNSAFE_DEFAULT_BEHAVIOR);
        } elseif (PluginEnvironmentHelpers::isWPConfigConstantDefined(PluginEnvironment::SETTINGS_OPTION_ENABLE_UNSAFE_DEFAULT_BEHAVIOR)) {
            /**
             * If wp-config.php constant `GRAPHQL_API_SETTINGS_OPTION_ENABLE_UNSAFE_DEFAULT_BEHAVIOR` is defined
             */
            self::$areUnsafeDefaultsEnabled = (bool)PluginEnvironmentHelpers::getWPConfigConstantValue(PluginEnvironment::SETTINGS_OPTION_ENABLE_UNSAFE_DEFAULT_BEHAVIOR);
        } else {
            /**
             * Base case: If on the DEV or PROD environment
             */
            self::$areUnsafeDefaultsEnabled = RootEnvironment::isApplicationEnvironmentDev();
        }
        return self::$areUnsafeDefaultsEnabled;
    }
}
