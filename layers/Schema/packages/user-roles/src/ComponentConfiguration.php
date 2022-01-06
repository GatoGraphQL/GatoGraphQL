<?php

declare(strict_types=1);

namespace PoPSchema\UserRoles;

use PoP\BasicService\Component\AbstractComponentConfiguration;
use PoP\BasicService\Component\EnvironmentValueHelpers;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    public function treatUserRoleAsAdminData(): bool
    {
        $envVariable = Environment::TREAT_USER_ROLE_AS_ADMIN_DATA;
        $defaultValue = true;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    public function treatUserCapabilityAsAdminData(): bool
    {
        $envVariable = Environment::TREAT_USER_CAPABILITY_AS_ADMIN_DATA;
        $defaultValue = true;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }
}
