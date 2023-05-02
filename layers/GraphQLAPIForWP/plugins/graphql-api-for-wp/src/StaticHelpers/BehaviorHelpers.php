<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\StaticHelpers;

use GraphQLAPI\GraphQLAPI\Constants\ResetSettingsOptions;
use GraphQLAPI\GraphQLAPI\Facades\UserSettingsManagerFacade;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\PluginManagementFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\PluginEnvironment;
use GraphQLAPI\GraphQLAPI\PluginManagement\PluginOptionsFormHandler;
use PoPSchema\SchemaCommons\Constants\Behaviors;

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
        if (self::$areNonRestrictiveDefaultsEnabled === null) {
            self::$areNonRestrictiveDefaultsEnabled = static::doAreNonRestrictiveDefaultsEnabled();
        }
        return self::$areNonRestrictiveDefaultsEnabled;
    }

    protected static function doAreNonRestrictiveDefaultsEnabled(): bool
    {
        $pluginOptionsFormHandler = new PluginOptionsFormHandler();

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
            return $useRestrictiveOrNotDefaultBehavior === ResetSettingsOptions::NON_RESTRICTIVE;
        }
        
        /**
         * Take stored value of Settings => Reset Settings on DB
         */
        $userSettingsManager = UserSettingsManagerFacade::getInstance();
        if (
            $userSettingsManager->hasSetting(
                PluginManagementFunctionalityModuleResolver::RESET_SETTINGS,
                PluginManagementFunctionalityModuleResolver::OPTION_USE_RESTRICTIVE_OR_NOT_DEFAULT_BEHAVIOR
            )
        ) {
            $useRestrictiveOrNotDefaultBehavior = $userSettingsManager->getSetting(
                PluginManagementFunctionalityModuleResolver::RESET_SETTINGS,
                PluginManagementFunctionalityModuleResolver::OPTION_USE_RESTRICTIVE_OR_NOT_DEFAULT_BEHAVIOR
            );
            return $useRestrictiveOrNotDefaultBehavior === ResetSettingsOptions::NON_RESTRICTIVE;
        }
        
        /**
         * If env var `SETTINGS_OPTION_ENABLE_RESTRICTIVE_DEFAULT_BEHAVIOR` is defined
         */
        if (getenv(PluginEnvironment::SETTINGS_OPTION_ENABLE_RESTRICTIVE_DEFAULT_BEHAVIOR) !== false) {
            return (bool)getenv(PluginEnvironment::SETTINGS_OPTION_ENABLE_RESTRICTIVE_DEFAULT_BEHAVIOR);
        }
        
        /**
         * If wp-config.php constant `GRAPHQL_API_SETTINGS_OPTION_ENABLE_RESTRICTIVE_DEFAULT_BEHAVIOR` is defined
         */
        if (PluginEnvironmentHelpers::isWPConfigConstantDefined(PluginEnvironment::SETTINGS_OPTION_ENABLE_RESTRICTIVE_DEFAULT_BEHAVIOR)) {
            return (bool)PluginEnvironmentHelpers::getWPConfigConstantValue(PluginEnvironment::SETTINGS_OPTION_ENABLE_RESTRICTIVE_DEFAULT_BEHAVIOR);
        }

        /**
         * Base case: Non-restrictive is the default behavior
         */
        return true;
    }
}
