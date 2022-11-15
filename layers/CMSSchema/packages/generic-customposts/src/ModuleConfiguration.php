<?php

declare(strict_types=1);

namespace PoPCMSSchema\GenericCustomPosts;

use PoP\Root\Module\AbstractModuleConfiguration;
use PoP\Root\Module\EnvironmentValueHelpers;

class ModuleConfiguration extends AbstractModuleConfiguration
{
    public function getGenericCustomPostListDefaultLimit(): ?int
    {
        $envVariable = Environment::GENERIC_CUSTOMPOST_LIST_DEFAULT_LIMIT;
        $defaultValue = 10;
        $callback = EnvironmentValueHelpers::toInt(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    public function getGenericCustomPostListMaxLimit(): ?int
    {
        $envVariable = Environment::GENERIC_CUSTOMPOST_LIST_MAX_LIMIT;
        $defaultValue = -1; // Unlimited
        $callback = EnvironmentValueHelpers::toInt(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }
}
