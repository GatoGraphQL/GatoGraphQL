<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\StaticHelpers;

use GatoGraphQL\GatoGraphQL\Constants\ResetSettingsOptions;
use GatoGraphQL\GatoGraphQL\Facades\UserSettingsManagerFacade;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\PluginManagementFunctionalityModuleResolver;
use GatoGraphQL\GatoGraphQL\PluginEnvironment;
use GatoGraphQL\GatoGraphQL\PluginManagement\PluginOptionsFormHandler;
use GatoGraphQL\GatoGraphQL\PluginMetadata;

class BehaviorHelpers
{
    private static ?bool $areRestrictiveDefaultsEnabled = null;

    public static function areRestrictiveDefaultsEnabled(): bool
    {
        if (self::$areRestrictiveDefaultsEnabled === null) {
            self::$areRestrictiveDefaultsEnabled = static::doAreRestrictiveDefaultsEnabled();
        }
        return self::$areRestrictiveDefaultsEnabled;
    }

    protected static function doAreRestrictiveDefaultsEnabled(): bool
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
            return $useRestrictiveOrNotDefaultBehavior === ResetSettingsOptions::RESTRICTIVE;
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
            return $useRestrictiveOrNotDefaultBehavior === ResetSettingsOptions::RESTRICTIVE;
        }

        /**
         * If env var `SETTINGS_OPTION_ENABLE_RESTRICTIVE_DEFAULT_BEHAVIOR` is defined
         */
        if (getenv(PluginEnvironment::SETTINGS_OPTION_ENABLE_RESTRICTIVE_DEFAULT_BEHAVIOR) !== false) {
            return strtolower(getenv(PluginEnvironment::SETTINGS_OPTION_ENABLE_RESTRICTIVE_DEFAULT_BEHAVIOR)) === "true";
        }

        /**
         * If wp-config.php constant `GATOGRAPHQL_SETTINGS_OPTION_ENABLE_RESTRICTIVE_DEFAULT_BEHAVIOR` is defined.
         *
         * Use a static namespace because here we don't have the value
         * set via the PluginInitializationConfiguration
         */
        if (PluginEnvironmentHelpers::isWPConfigConstantDefined(PluginEnvironment::SETTINGS_OPTION_ENABLE_RESTRICTIVE_DEFAULT_BEHAVIOR, PluginMetadata::WPCONFIG_CONST_NAMESPACE)) {
            return strtolower(PluginEnvironmentHelpers::getWPConfigConstantValue(PluginEnvironment::SETTINGS_OPTION_ENABLE_RESTRICTIVE_DEFAULT_BEHAVIOR, PluginMetadata::WPCONFIG_CONST_NAMESPACE)) === "true";
        }

        /**
         * Base case: Restrictive is NOT the default behavior
         * (non-restrictive is)
         */
        return false;
    }
}
