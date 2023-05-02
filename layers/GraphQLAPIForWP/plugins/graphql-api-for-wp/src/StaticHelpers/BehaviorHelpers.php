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
    private static ?bool $areNonRestrictiveDefaultsEnabled = null;

    public static function getDefaultBehavior(): string
    {
        return static::areNonRestrictiveDefaultsEnabled()
            ? Behaviors::DENY
            : Behaviors::ALLOW;
    }

    public static function areNonRestrictiveDefaultsEnabled(): bool
    {
        if (self::$areNonRestrictiveDefaultsEnabled !== null) {
            return self::$areNonRestrictiveDefaultsEnabled;
        }

        $pluginOptionsFormHandler = new PluginOptionsFormHandler();
        $userSettingsManager = UserSettingsManagerFacade::getInstance();

        /**
         * If Settings => Reset Settings was just submitted
         * (i.e. we are in options.php)
         */
        $useRestrictiveOrNotDefaultBehavior = $pluginOptionsFormHandler->maybeOverrideValueFromForm(
            null,
            PluginManagementFunctionalityModuleResolver::RESET_SETTINGS,
            PluginManagementFunctionalityModuleResolver::OPTION_USE_RESTRICTIVE_OR_NOT_DEFAULT_BEHAVIOR
        );
        if ($useRestrictiveOrNotDefaultBehavior !== null) {
            self::$areNonRestrictiveDefaultsEnabled = $useRestrictiveOrNotDefaultBehavior === ResetSettingsOptions::NON_RESTRICTIVE;
        } elseif (
            $userSettingsManager->hasSetting(
                PluginManagementFunctionalityModuleResolver::RESET_SETTINGS,
                PluginManagementFunctionalityModuleResolver::OPTION_USE_RESTRICTIVE_OR_NOT_DEFAULT_BEHAVIOR
            )
        ) {
            /**
             * Take stored value of Settings => Reset Settings on DB
             */
            $useRestrictiveOrNotDefaultBehavior = $userSettingsManager->getSetting(
                PluginManagementFunctionalityModuleResolver::RESET_SETTINGS,
                PluginManagementFunctionalityModuleResolver::OPTION_USE_RESTRICTIVE_OR_NOT_DEFAULT_BEHAVIOR
            );
            self::$areNonRestrictiveDefaultsEnabled = $useRestrictiveOrNotDefaultBehavior === ResetSettingsOptions::NON_RESTRICTIVE;
        } elseif (getenv(PluginEnvironment::SETTINGS_OPTION_ENABLE_NON_RESTRICTIVE_DEFAULT_BEHAVIOR) !== false) {
            /**
             * If env var `SETTINGS_OPTION_ENABLE_NON_RESTRICTIVE_DEFAULT_BEHAVIOR` is defined
             */
            self::$areNonRestrictiveDefaultsEnabled = (bool)getenv(PluginEnvironment::SETTINGS_OPTION_ENABLE_NON_RESTRICTIVE_DEFAULT_BEHAVIOR);
        } elseif (PluginEnvironmentHelpers::isWPConfigConstantDefined(PluginEnvironment::SETTINGS_OPTION_ENABLE_NON_RESTRICTIVE_DEFAULT_BEHAVIOR)) {
            /**
             * If wp-config.php constant `GRAPHQL_API_SETTINGS_OPTION_ENABLE_NON_RESTRICTIVE_DEFAULT_BEHAVIOR` is defined
             */
            self::$areNonRestrictiveDefaultsEnabled = (bool)PluginEnvironmentHelpers::getWPConfigConstantValue(PluginEnvironment::SETTINGS_OPTION_ENABLE_NON_RESTRICTIVE_DEFAULT_BEHAVIOR);
        } else {
            /**
             * Base case: If on the DEV or PROD environment
             */
            self::$areNonRestrictiveDefaultsEnabled = RootEnvironment::isApplicationEnvironmentDev();
        }
        return self::$areNonRestrictiveDefaultsEnabled;
    }
}
