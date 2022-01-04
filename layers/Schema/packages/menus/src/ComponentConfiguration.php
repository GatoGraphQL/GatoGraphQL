<?php

declare(strict_types=1);

namespace PoPSchema\Menus;

use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;

class ComponentConfiguration extends \PoP\BasicService\Component\AbstractComponentConfiguration
{
    private static ?int $getMenuListDefaultLimit = 10;
    private static ?int $getMenuListMaxLimit = -1;

    public static function getMenuListDefaultLimit(): ?int
    {
        // Define properties
        $envVariable = Environment::MENU_LIST_DEFAULT_LIMIT;
        $selfProperty = &self::$getMenuListDefaultLimit;
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

    public static function getMenuListMaxLimit(): ?int
    {
        // Define properties
        $envVariable = Environment::MENU_LIST_MAX_LIMIT;
        $selfProperty = &self::$getMenuListMaxLimit;
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
