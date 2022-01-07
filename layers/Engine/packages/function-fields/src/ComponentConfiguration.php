<?php

declare(strict_types=1);

namespace PoP\FunctionFields;

use PoP\BasicService\Component\AbstractComponentConfiguration;
use PoP\BasicService\Component\EnvironmentValueHelpers;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    public function disableFunctionFields(): bool
    {
        $envVariable = Environment::DISABLE_FUNCTION_FIELDS;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    protected function enableHook(string $envVariable): bool
    {
        return match ($envVariable) {
            Environment::DISABLE_FUNCTION_FIELDS => false,
            default => parent::enableHook($envVariable),
        };
    }
}
