<?php

declare(strict_types=1);

namespace PoPSchema\GenericCustomPosts;

use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;
use PoP\ComponentModel\ComponentConfiguration\ComponentConfigurationTrait;

class ComponentConfiguration
{
    use ComponentConfigurationTrait;

    private static ?int $getGenericCustomPostListDefaultLimit = 10;
    // private static ?int $getGenericCustomPostListMaxLimit = -1;
    private static array $getGenericCustomPostTypes = ['post'];

    public static function getGenericCustomPostListDefaultLimit(): ?int
    {
        // Define properties
        $envVariable = Environment::GENERIC_CUSTOMPOST_LIST_DEFAULT_LIMIT;
        $selfProperty = &self::$getGenericCustomPostListDefaultLimit;
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

    // public static function getGenericCustomPostListMaxLimit(): ?int
    // {
    //     // Define properties
    //     $envVariable = Environment::GENERIC_CUSTOMPOST_LIST_MAX_LIMIT;
    //     $selfProperty = &self::$getGenericCustomPostListMaxLimit;
    //     $defaultValue = -1; // Unlimited
    //     $callback = [EnvironmentValueHelpers::class, 'toInt'];

    //     // Initialize property from the environment/hook
    //     self::maybeInitializeConfigurationValue(
    //         $envVariable,
    //         $selfProperty,
    //         $defaultValue,
    //         $callback
    //     );
    //     return $selfProperty;
    // }

    public static function getGenericCustomPostTypes(): array
    {
        // Define properties
        $envVariable = Environment::GENERIC_CUSTOMPOST_TYPES;
        $selfProperty = &self::$getGenericCustomPostTypes;
        $defaultValue = ['post'];
        $callback = [EnvironmentValueHelpers::class, 'commaSeparatedStringToArray'];

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
