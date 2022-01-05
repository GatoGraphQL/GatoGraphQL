<?php

declare(strict_types=1);

namespace PoPSchema\UserAvatars;

use PoP\BasicService\Component\AbstractComponentConfiguration;
use PoP\BasicService\Component\EnvironmentValueHelpers;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    private int $getUserAvatarDefaultSize = 96;

    public function getUserAvatarDefaultSize(): int
    {
        // Define properties
        $envVariable = Environment::USER_AVATAR_DEFAULT_SIZE;
        $selfProperty = &$this->getUserAvatarDefaultSize;
        $defaultValue = 96;
        $callback = [EnvironmentValueHelpers::class, 'toInt'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $defaultValue,
            $callback,
        );
        return $this->configuration[$envVariable];
    }
}
