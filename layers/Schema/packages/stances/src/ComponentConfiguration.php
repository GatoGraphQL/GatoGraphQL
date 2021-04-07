<?php

declare(strict_types=1);

namespace PoPSchema\Stances;

use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;
use PoP\ComponentModel\ComponentConfiguration\ComponentConfigurationTrait;

class ComponentConfiguration
{
    use ComponentConfigurationTrait;

    private static ?int $getStanceListDefaultLimit = 10;
    private static ?int $getStanceListMaxLimit = -1;

    public static function getStanceListDefaultLimit(): ?int
    {
        // Define properties
        $envVariable = Environment::STANCE_LIST_DEFAULT_LIMIT;
        $selfProperty = &self::$getStanceListDefaultLimit;
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

    public static function getStanceListMaxLimit(): ?int
    {
        // Define properties
        $envVariable = Environment::STANCE_LIST_MAX_LIMIT;
        $selfProperty = &self::$getStanceListMaxLimit;
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
