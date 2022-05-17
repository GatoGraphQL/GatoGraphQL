<?php

declare(strict_types=1);

namespace PoP\Root;

use PoP\Root\Module\AbstractModuleConfiguration;
use PoP\Root\Module\EnvironmentValueHelpers;

class ComponentConfiguration extends AbstractModuleConfiguration
{
    public function enablePassingStateViaRequest(): bool
    {
        $envVariable = Environment::ENABLE_PASSING_STATE_VIA_REQUEST;
        $defaultValue = false;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    public function enablePassingRoutingStateViaRequest(): bool
    {
        if (!$this->enablePassingStateViaRequest()) {
            return false;
        }

        $envVariable = Environment::ENABLE_PASSING_ROUTING_STATE_VIA_REQUEST;
        $defaultValue = false;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    protected function enableHook(string $envVariable): bool
    {
        return match ($envVariable) {
            Environment::ENABLE_PASSING_STATE_VIA_REQUEST,
            Environment::ENABLE_PASSING_ROUTING_STATE_VIA_REQUEST =>
                false,
            default => parent::enableHook($envVariable),
        };
    }
}
