<?php

declare(strict_types=1);

namespace PoPSchema\PostTags;

class ComponentConfiguration extends \PoP\BasicService\Component\AbstractComponentConfiguration
{
    // private static ?int $getPostTagListDefaultLimit = 10;
    // private static ?int $getPostTagListMaxLimit = -1;
    private static string $getPostTagsRoute = '';

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

    public static function getPostTagsRoute(): string
    {
        // Define properties
        $envVariable = Environment::POSTTAGS_ROUTE;
        $selfProperty = &self::$getPostTagsRoute;
        $defaultValue = 'tags';

        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue
        );
        return $selfProperty;
    }
}
