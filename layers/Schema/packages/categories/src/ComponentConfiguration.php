<?php

declare(strict_types=1);

namespace PoPSchema\Categories;

use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;
use PoP\ComponentModel\ComponentConfiguration\ComponentConfigurationTrait;

class ComponentConfiguration
{
    use ComponentConfigurationTrait;

    private static $getCategoryListDefaultLimit;
    private static $getCategoryListMaxLimit;

    public static function getCategoryListDefaultLimit(): ?int
    {
        // Define properties
        $envVariable = Environment::CATEGORY_LIST_DEFAULT_LIMIT;
        $selfProperty = &self::$getCategoryListDefaultLimit;
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

    public static function getCategoryListMaxLimit(): ?int
    {
        // Define properties
        $envVariable = Environment::CATEGORY_LIST_MAX_LIMIT;
        $selfProperty = &self::$getCategoryListMaxLimit;
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
