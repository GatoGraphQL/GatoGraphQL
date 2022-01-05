<?php

declare(strict_types=1);

namespace PoPSchema\Users;

use PoP\BasicService\Component\AbstractComponentConfiguration;
use PoP\BasicService\Component\EnvironmentValueHelpers;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    private ?int $getUserListDefaultLimit = 10;
    private ?int $getUserListMaxLimit = -1;
    private string $getUsersRoute = '';
    private bool $treatUserEmailAsAdminData = true;

    public function getUserListDefaultLimit(): ?int
    {
        // Define properties
        $envVariable = Environment::USER_LIST_DEFAULT_LIMIT;
        $selfProperty = &$this->getUserListDefaultLimit;
        $defaultValue = 10;
        $callback = [EnvironmentValueHelpers::class, 'toInt'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $defaultValue,
            $callback,
        );
        return $this->configuration[$envVariable];
    }

    public function getUserListMaxLimit(): ?int
    {
        // Define properties
        $envVariable = Environment::USER_LIST_MAX_LIMIT;
        $selfProperty = &$this->getUserListMaxLimit;
        $defaultValue = -1; // Unlimited
        $callback = [EnvironmentValueHelpers::class, 'toInt'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $defaultValue,
            $callback,
        );
        return $this->configuration[$envVariable];
    }

    public function getUsersRoute(): string
    {
        // Define properties
        $envVariable = Environment::USERS_ROUTE;
        $selfProperty = &$this->getUsersRoute;
        $defaultValue = 'users';

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $defaultValue,
        );
        return $this->configuration[$envVariable];
    }

    public function treatUserEmailAsAdminData(): bool
    {
        // Define properties
        $envVariable = Environment::TREAT_USER_EMAIL_AS_ADMIN_DATA;
        $selfProperty = &$this->treatUserEmailAsAdminData;
        $defaultValue = true;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $defaultValue,
            $callback,
        );
        return $this->configuration[$envVariable];
    }
}
