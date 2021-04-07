<?php

declare(strict_types=1);

namespace PoPSchema\Settings;

use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;
use PoP\ComponentModel\ComponentConfiguration\ComponentConfigurationTrait;

class ComponentConfiguration
{
    use ComponentConfigurationTrait;

    private static $getSettingsListDefaultLimit;
    private static $getSettingsListMaxLimit;

    public static function getSettingsListDefaultLimit(): ?int
    {
        // Define properties
        $envVariable = Environment::USER_LIST_DEFAULT_LIMIT;
        $selfProperty = &self::$getSettingsListDefaultLimit;
        $defaultValue = 10;
        $callback = [EnvironmentValueHelpers::class, 'toInt'];

        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    public static function getSettingsListMaxLimit(): ?int
    {
        // Define properties
        $envVariable = Environment::USER_LIST_MAX_LIMIT;
        $selfProperty = &self::$getSettingsListMaxLimit;
        $defaultValue = -1; // Unlimited
        $callback = [EnvironmentValueHelpers::class, 'toInt'];

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
