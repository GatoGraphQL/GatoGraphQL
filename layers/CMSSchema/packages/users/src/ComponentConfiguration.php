<?php

declare(strict_types=1);

namespace PoPCMSSchema\Users;

use PoP\Root\Component\AbstractComponentConfiguration;
use PoP\Root\Component\EnvironmentValueHelpers;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    public function getUserListDefaultLimit(): ?int
    {
        $envVariable = Environment::USER_LIST_DEFAULT_LIMIT;
        $defaultValue = 10;
        $callback = [EnvironmentValueHelpers::class, 'toInt'];

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    public function getUserListMaxLimit(): ?int
    {
        $envVariable = Environment::USER_LIST_MAX_LIMIT;
        $defaultValue = -1; // Unlimited
        $callback = [EnvironmentValueHelpers::class, 'toInt'];

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    public function getUsersRoute(): string
    {
        $envVariable = Environment::USERS_ROUTE;
        $defaultValue = 'users';

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
        );
    }

    public function treatUserEmailAsAdminData(): bool
    {
        $envVariable = Environment::TREAT_USER_EMAIL_AS_ADMIN_DATA;
        $defaultValue = true;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }
}
