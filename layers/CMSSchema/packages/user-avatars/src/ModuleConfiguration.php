<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserAvatars;

use PoP\Root\Module\AbstractModuleConfiguration;
use PoP\Root\Module\EnvironmentValueHelpers;

class ModuleConfiguration extends AbstractModuleConfiguration
{
    public function getUserAvatarDefaultSize(): int
    {
        $envVariable = Environment::USER_AVATAR_DEFAULT_SIZE;
        $defaultValue = 96;
        $callback = EnvironmentValueHelpers::toInt(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }
}
