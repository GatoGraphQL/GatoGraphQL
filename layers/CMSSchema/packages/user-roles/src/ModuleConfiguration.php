<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserRoles;

use PoP\Root\Module\AbstractModuleConfiguration;
use PoP\Root\Module\EnvironmentValueHelpers;

class ModuleConfiguration extends AbstractModuleConfiguration
{
    public function treatUserRoleAsSensitiveData(): bool
    {
        $envVariable = Environment::TREAT_USER_ROLE_AS_SENSITIVE_DATA;
        $defaultValue = true;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    public function treatUserCapabilityAsSensitiveData(): bool
    {
        $envVariable = Environment::TREAT_USER_CAPABILITY_AS_SENSITIVE_DATA;
        $defaultValue = true;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }
}
