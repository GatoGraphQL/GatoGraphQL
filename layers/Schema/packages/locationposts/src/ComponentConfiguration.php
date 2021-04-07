<?php

declare(strict_types=1);

namespace PoPSchema\LocationPosts;

use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;
use PoP\ComponentModel\ComponentConfiguration\ComponentConfigurationTrait;

class ComponentConfiguration
{
    use ComponentConfigurationTrait;

    private static ?int $getLocationPostListDefaultLimit = 10;
    private static ?int $getLocationPostListMaxLimit = -1;

    public static function getLocationPostListDefaultLimit(): ?int
    {
        // Define properties
        $envVariable = Environment::LOCATIONPOST_LIST_DEFAULT_LIMIT;
        $selfProperty = &self::$getLocationPostListDefaultLimit;
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

    public static function getLocationPostListMaxLimit(): ?int
    {
        // Define properties
        $envVariable = Environment::LOCATIONPOST_LIST_MAX_LIMIT;
        $selfProperty = &self::$getLocationPostListMaxLimit;
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
