<?php

declare(strict_types=1);

namespace PoPWPSchema\CustomPosts;

use PoP\Root\Module\AbstractModuleConfiguration;
use PoP\Root\Module\EnvironmentValueHelpers;

class ModuleConfiguration extends AbstractModuleConfiguration
{
    public function treatCustomPostEditURLAsSensitiveData(): bool
    {
        $envVariable = Environment::TREAT_CUSTOMPOST_EDIT_URL_AS_SENSITIVE_DATA;
        $defaultValue = true;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }
}
