<?php

declare(strict_types=1);

namespace PoPSchema\UserAvatars;

use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;

class ComponentConfiguration extends \PoP\BasicService\Component\AbstractComponentConfiguration
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
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }
}
