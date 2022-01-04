<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts;

use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;

class ComponentConfiguration extends \PoP\BasicService\Component\AbstractComponentConfiguration
{
    private static ?int $getCustomPostListDefaultLimit = 10;
    private static ?int $getCustomPostListMaxLimit = -1;
    private static bool $useSingleTypeInsteadOfCustomPostUnionType = false;
    private static bool $treatCustomPostStatusAsAdminData = true;

    public static function getCustomPostListDefaultLimit(): ?int
    {
        // Define properties
        $envVariable = Environment::CUSTOMPOST_LIST_DEFAULT_LIMIT;
        $selfProperty = &self::$getCustomPostListDefaultLimit;
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

    public static function getCustomPostListMaxLimit(): ?int
    {
        // Define properties
        $envVariable = Environment::CUSTOMPOST_LIST_MAX_LIMIT;
        $selfProperty = &self::$getCustomPostListMaxLimit;
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

    public static function useSingleTypeInsteadOfCustomPostUnionType(): bool
    {
        // Define properties
        $envVariable = Environment::USE_SINGLE_TYPE_INSTEAD_OF_CUSTOMPOST_UNION_TYPE;
        $selfProperty = &self::$useSingleTypeInsteadOfCustomPostUnionType;
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

    public static function treatCustomPostStatusAsAdminData(): bool
    {
        // Define properties
        $envVariable = Environment::TREAT_CUSTOMPOST_STATUS_AS_ADMIN_DATA;
        $selfProperty = &self::$treatCustomPostStatusAsAdminData;
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
