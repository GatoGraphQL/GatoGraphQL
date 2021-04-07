<?php

declare(strict_types=1);

namespace PoP\AccessControl;

use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;
use PoP\ComponentModel\ComponentConfiguration\ComponentConfigurationTrait;

class ComponentConfiguration
{
    use ComponentConfigurationTrait;

    private static bool $usePrivateSchemaMode = false;
    private static bool $enableIndividualControlForPublicPrivateSchemaMode = true;

    public static function usePrivateSchemaMode(): bool
    {
        // Define properties
        $envVariable = Environment::USE_PRIVATE_SCHEMA_MODE;
        $selfProperty = &self::$usePrivateSchemaMode;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    public static function enableIndividualControlForPublicPrivateSchemaMode(): bool
    {
        // Define properties
        $envVariable = Environment::ENABLE_INDIVIDUAL_CONTROL_FOR_PUBLIC_PRIVATE_SCHEMA_MODE;
        $selfProperty = &self::$enableIndividualControlForPublicPrivateSchemaMode;
        $defaultValue = true;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    /**
     * If either constant `USE_PRIVATE_SCHEMA_MODE` or `ENABLE_INDIVIDUAL_CONTROL_FOR_PUBLIC_PRIVATE_SCHEMA_MODE`
     * (which enables to define the private schema mode for a specific entry) is true,
     * then the schema (as obtained by querying the "__schema" field) is dynamic:
     * Fields will be available or not depending on the user being logged in or not
     */
    public static function canSchemaBePrivate(): bool
    {
        return
            self::enableIndividualControlForPublicPrivateSchemaMode()
            || self::usePrivateSchemaMode();
    }
}
