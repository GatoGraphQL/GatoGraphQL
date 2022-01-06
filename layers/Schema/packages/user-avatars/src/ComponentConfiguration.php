<?php

declare(strict_types=1);

namespace PoPSchema\UserAvatars;

use PoP\BasicService\Component\AbstractComponentConfiguration;
use PoP\BasicService\Component\EnvironmentValueHelpers;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    public function getUserAvatarDefaultSize(): int
    {
        // Define properties
        $envVariable = Environment::USER_AVATAR_DEFAULT_SIZE;
        $defaultValue = 96;
        $callback = [EnvironmentValueHelpers::class, 'toInt'];

        // Initialize property from the environment/hook
        $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
        return $this->configuration[$envVariable];
    }
}
