<?php

declare(strict_types=1);

namespace PoPSchema\UserRoles;

use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;

class ComponentConfiguration extends \PoP\BasicService\Component\AbstractComponentConfiguration
{
    private static bool $treatUserRoleAsAdminData = true;
    private static bool $treatUserCapabilityAsAdminData = true;

    public static function treatUserRoleAsAdminData(): bool
    {
        // Define properties
        $envVariable = Environment::TREAT_USER_ROLE_AS_ADMIN_DATA;
        $selfProperty = &self::$treatUserRoleAsAdminData;
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

    public static function treatUserCapabilityAsAdminData(): bool
    {
        // Define properties
        $envVariable = Environment::TREAT_USER_CAPABILITY_AS_ADMIN_DATA;
        $selfProperty = &self::$treatUserCapabilityAsAdminData;
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
