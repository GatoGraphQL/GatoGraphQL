<?php

declare(strict_types=1);

namespace PoPSchema\UserRoles;

use PoP\BasicService\Component\AbstractComponentConfiguration;
use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    private bool $treatUserRoleAsAdminData = true;
    private bool $treatUserCapabilityAsAdminData = true;

    public function treatUserRoleAsAdminData(): bool
    {
        // Define properties
        $envVariable = Environment::TREAT_USER_ROLE_AS_ADMIN_DATA;
        $selfProperty = &$this->treatUserRoleAsAdminData;
        $defaultValue = true;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    public function treatUserCapabilityAsAdminData(): bool
    {
        // Define properties
        $envVariable = Environment::TREAT_USER_CAPABILITY_AS_ADMIN_DATA;
        $selfProperty = &$this->treatUserCapabilityAsAdminData;
        $defaultValue = true;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }
}
