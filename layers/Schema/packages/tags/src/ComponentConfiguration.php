<?php

declare(strict_types=1);

namespace PoPSchema\Tags;

use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;

class ComponentConfiguration extends \PoP\BasicService\Component\AbstractComponentConfiguration
{
    private static ?int $getTagListDefaultLimit = 10;
    private static ?int $getTagListMaxLimit = -1;

    public static function getTagListDefaultLimit(): ?int
    {
        // Define properties
        $envVariable = Environment::TAG_LIST_DEFAULT_LIMIT;
        $selfProperty = &self::$getTagListDefaultLimit;
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

    public static function getTagListMaxLimit(): ?int
    {
        // Define properties
        $envVariable = Environment::TAG_LIST_MAX_LIMIT;
        $selfProperty = &self::$getTagListMaxLimit;
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
