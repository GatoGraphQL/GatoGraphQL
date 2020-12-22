<?php

declare(strict_types=1);

namespace PoPSchema\Events;

use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;
use PoP\ComponentModel\ComponentConfiguration\ComponentConfigurationTrait;

class ComponentConfiguration
{
    use ComponentConfigurationTrait;

    private static $getEventListDefaultLimit;
    private static $getEventListMaxLimit;

    public static function getEventListDefaultLimit(): ?int
    {
        // Define properties
        $envVariable = Environment::EVENT_LIST_DEFAULT_LIMIT;
        $selfProperty = &self::$getEventListDefaultLimit;
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

    public static function getEventListMaxLimit(): ?int
    {
        // Define properties
        $envVariable = Environment::EVENT_LIST_MAX_LIMIT;
        $selfProperty = &self::$getEventListMaxLimit;
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
