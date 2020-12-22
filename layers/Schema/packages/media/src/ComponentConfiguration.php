<?php

declare(strict_types=1);

namespace PoPSchema\Media;

use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;
use PoP\ComponentModel\ComponentConfiguration\ComponentConfigurationTrait;

class ComponentConfiguration
{
    use ComponentConfigurationTrait;

    private static $getMediaListDefaultLimit;
    private static $getMediaListMaxLimit;

    public static function getMediaListDefaultLimit(): ?int
    {
        // Define properties
        $envVariable = Environment::MEDIA_LIST_DEFAULT_LIMIT;
        $selfProperty = &self::$getMediaListDefaultLimit;
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

    public static function getMediaListMaxLimit(): ?int
    {
        // Define properties
        $envVariable = Environment::MEDIA_LIST_MAX_LIMIT;
        $selfProperty = &self::$getMediaListMaxLimit;
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
