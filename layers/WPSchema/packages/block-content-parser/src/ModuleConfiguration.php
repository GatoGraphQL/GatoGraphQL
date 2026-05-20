<?php

declare(strict_types=1);

namespace PoPWPSchema\BlockContentParser;

use PoP\Root\Module\AbstractModuleConfiguration;
use PoP\Root\Module\EnvironmentValueHelpers;

class ModuleConfiguration extends AbstractModuleConfiguration
{
    public function addMissingBlockWarning(): bool
    {
        $envVariable = Environment::ADD_NOT_REGISTERED_BLOCK_WARNING;
        $defaultValue = true;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }
}
