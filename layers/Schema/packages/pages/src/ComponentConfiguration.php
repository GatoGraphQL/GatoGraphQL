<?php

declare(strict_types=1);

namespace PoPSchema\Pages;

use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;

class ComponentConfiguration extends \PoP\BasicService\Component\AbstractComponentConfiguration
{
    private static ?int $getPageListDefaultLimit = 10;
    private static ?int $getPageListMaxLimit = -1;
    private static bool $addPageTypeToCustomPostUnionTypes = false;

    public static function getPageListDefaultLimit(): ?int
    {
        // Define properties
        $envVariable = Environment::PAGE_LIST_DEFAULT_LIMIT;
        $selfProperty = &self::$getPageListDefaultLimit;
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

    public static function getPageListMaxLimit(): ?int
    {
        // Define properties
        $envVariable = Environment::PAGE_LIST_MAX_LIMIT;
        $selfProperty = &self::$getPageListMaxLimit;
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

    public static function addPageTypeToCustomPostUnionTypes(): bool
    {
        // Define properties
        $envVariable = Environment::ADD_PAGE_TYPE_TO_CUSTOMPOST_UNION_TYPES;
        $selfProperty = &self::$addPageTypeToCustomPostUnionTypes;
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
}
