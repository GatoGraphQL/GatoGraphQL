<?php

declare(strict_types=1);

namespace PoPSchema\PostTags;

use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;
use PoP\ComponentModel\ComponentConfiguration\ComponentConfigurationTrait;

class ComponentConfiguration
{
    use ComponentConfigurationTrait;

    // private static ?int $getPostTagListDefaultLimit = 10;
    // private static ?int $getPostTagListMaxLimit = -1;

    // public static function getPostTagListDefaultLimit(): ?int
    // {
    //     // Define properties
    //     $envVariable = Environment::POSTTAG_LIST_DEFAULT_LIMIT;
    //     $selfProperty = &self::$getPostTagListDefaultLimit;
    //     $defaultValue = 10;
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

    // public static function getPostTagListMaxLimit(): ?int
    // {
    //     // Define properties
    //     $envVariable = Environment::POSTTAG_LIST_MAX_LIMIT;
    //     $selfProperty = &self::$getPostTagListMaxLimit;
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
}
