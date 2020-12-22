<?php

declare(strict_types=1);

namespace PoPSchema\Posts;

use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;
use PoP\ComponentModel\ComponentConfiguration\ComponentConfigurationTrait;

class ComponentConfiguration
{
    use ComponentConfigurationTrait;

    private static $getPostListDefaultLimit;
    private static $getPostListMaxLimit;
    private static $addPostTypeToCustomPostUnionTypes;

    public static function getPostListDefaultLimit(): ?int
    {
        // Define properties
        $envVariable = Environment::POST_LIST_DEFAULT_LIMIT;
        $selfProperty = &self::$getPostListDefaultLimit;
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

    public static function getPostListMaxLimit(): ?int
    {
        // Define properties
        $envVariable = Environment::POST_LIST_MAX_LIMIT;
        $selfProperty = &self::$getPostListMaxLimit;
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

    public static function addPostTypeToCustomPostUnionTypes(): bool
    {
        // Define properties
        $envVariable = Environment::ADD_POST_TYPE_TO_CUSTOMPOST_UNION_TYPES;
        $selfProperty = &self::$addPostTypeToCustomPostUnionTypes;
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
