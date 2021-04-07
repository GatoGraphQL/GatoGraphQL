<?php

declare(strict_types=1);

namespace PoPSchema\Settings;

use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;
use PoP\ComponentModel\ComponentConfiguration\ComponentConfigurationTrait;

class ComponentConfiguration
{
    use ComponentConfigurationTrait;

    private static $getUserListDefaultLimit;
    private static $getUserListMaxLimit;

    public static function getUserListDefaultLimit(): ?int
    {
        // Define properties
        $envVariable = Environment::USER_LIST_DEFAULT_LIMIT;
        $selfProperty = &self::$getUserListDefaultLimit;
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

    public static function getUserListMaxLimit(): ?int
    {
        // Define properties
        $envVariable = Environment::USER_LIST_MAX_LIMIT;
        $selfProperty = &self::$getUserListMaxLimit;
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
