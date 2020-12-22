<?php

declare(strict_types=1);

namespace PoP\AccessControl;

use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;
use PoP\ComponentModel\ComponentConfiguration\ComponentConfigurationTrait;

class ComponentConfiguration
{
    use ComponentConfigurationTrait;

    private static $usePrivateSchemaMode;
    private static $enableIndividualControlForPublicPrivateSchemaMode;

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
}
