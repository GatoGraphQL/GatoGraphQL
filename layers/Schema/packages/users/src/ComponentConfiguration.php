<?php

declare(strict_types=1);

namespace PoPSchema\Users;

use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;

class ComponentConfiguration extends \PoP\BasicService\Component\AbstractComponentConfiguration
{
    private static ?int $getUserListDefaultLimit = 10;
    private static ?int $getUserListMaxLimit = -1;
    private static string $getUsersRoute = '';
    private static bool $treatUserEmailAsAdminData = true;

    public static function getUserListDefaultLimit(): ?int
    {
        // Define properties
        $envVariable = Environment::USER_LIST_DEFAULT_LIMIT;
        $selfProperty = &self::$getUserListDefaultLimit;
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

    public static function getUserListMaxLimit(): ?int
    {
        // Define properties
        $envVariable = Environment::USER_LIST_MAX_LIMIT;
        $selfProperty = &self::$getUserListMaxLimit;
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

    public static function getUsersRoute(): string
    {
        // Define properties
        $envVariable = Environment::USERS_ROUTE;
        $selfProperty = &self::$getUsersRoute;
        $defaultValue = 'users';

        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue
        );
        return $selfProperty;
    }

    public static function treatUserEmailAsAdminData(): bool
    {
        // Define properties
        $envVariable = Environment::TREAT_USER_EMAIL_AS_ADMIN_DATA;
        $selfProperty = &self::$treatUserEmailAsAdminData;
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
