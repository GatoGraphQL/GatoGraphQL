<?php

declare(strict_types=1);

namespace PoPSchema\UserAvatars;

use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;
use PoP\ComponentModel\ComponentConfiguration\ComponentConfigurationTrait;

class ComponentConfiguration
{
    use ComponentConfigurationTrait;

    private static int $getUserAvatarDefaultSize = 96;

    public static function getUserAvatarDefaultSize(): int
    {
        // Define properties
        $envVariable = Environment::USER_AVATAR_DEFAULT_SIZE;
        $selfProperty = &self::$getUserAvatarDefaultSize;
        $defaultValue = 96;
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
