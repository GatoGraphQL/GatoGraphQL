<?php

declare(strict_types=1);

namespace PoPSchema\PostCategories;

class ComponentConfiguration extends \PoP\BasicService\Component\AbstractComponentConfiguration
{
    // private static int $getPostCategoryListDefaultLimit = 10;
    // private static int $getPostCategoryListMaxLimit = -1;
    private static string $getPostCategoriesRoute = '';

    // public static function getPostCategoryListDefaultLimit(): ?int
    // {
    //     // Define properties
    //     $envVariable = Environment::POSTCATEGORY_LIST_DEFAULT_LIMIT;
    //     $selfProperty = &self::$getPostCategoryListDefaultLimit;
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

    // public static function getPostCategoryListMaxLimit(): ?int
    // {
    //     // Define properties
    //     $envVariable = Environment::POSTCATEGORY_LIST_MAX_LIMIT;
    //     $selfProperty = &self::$getPostCategoryListMaxLimit;
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

    public static function getPostCategoriesRoute(): string
    {
        // Define properties
        $envVariable = Environment::POSTCATEGORIES_ROUTE;
        $selfProperty = &self::$getPostCategoriesRoute;
        $defaultValue = 'categories';

        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue
        );
        return $selfProperty;
    }
}
