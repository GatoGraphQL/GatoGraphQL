<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserRoles;

use PoP\Root\Module\AbstractComponentConfiguration;
use PoP\Root\Module\EnvironmentValueHelpers;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    public function treatUserRoleAsAdminData(): bool
    {
        $envVariable = Environment::TREAT_USER_ROLE_AS_ADMIN_DATA;
        $defaultValue = true;
        $callback = EnvironmentValueHelpers::toBool(...);

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
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }
}
