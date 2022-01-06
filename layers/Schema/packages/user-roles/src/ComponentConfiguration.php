<?php

declare(strict_types=1);

namespace PoPSchema\UserRoles;

use PoP\BasicService\Component\AbstractComponentConfiguration;
use PoP\BasicService\Component\EnvironmentValueHelpers;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    public function treatUserRoleAsAdminData(): bool
    {
        // Define properties
        $envVariable = Environment::TREAT_USER_ROLE_AS_ADMIN_DATA;
        $defaultValue = true;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        $this->getConfigurationValueFromEnvVariable(
            $envVariable,
            $defaultValue,
            $callback,
        );
        return $this->configuration[$envVariable];
    }

    public function treatUserCapabilityAsAdminData(): bool
    {
        // Define properties
        $envVariable = Environment::TREAT_USER_CAPABILITY_AS_ADMIN_DATA;
        $defaultValue = true;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        $this->getConfigurationValueFromEnvVariable(
            $envVariable,
            $defaultValue,
            $callback,
        );
        return $this->configuration[$envVariable];
    }
}
