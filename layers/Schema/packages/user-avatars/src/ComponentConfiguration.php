<?php

declare(strict_types=1);

namespace PoPSchema\UserAvatars;

use PoP\Root\Component\AbstractComponentConfiguration;
use PoP\BasicService\Component\EnvironmentValueHelpers;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    public function getUserAvatarDefaultSize(): int
    {
        $envVariable = Environment::USER_AVATAR_DEFAULT_SIZE;
        $defaultValue = 96;
        $callback = [EnvironmentValueHelpers::class, 'toInt'];

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }
}
