<?php

declare(strict_types=1);

namespace PoPSchema\DirectiveCommons;

use PoP\Root\Module\AbstractModuleConfiguration;
use PoP\Root\Module\EnvironmentValueHelpers;

class ModuleConfiguration extends AbstractModuleConfiguration
{
    public function bubbleUpErrorsInMetaDirectives(): bool
    {
        $envVariable = Environment::BUBBLE_UP_ERRORS_IN_META_DIRECTIVES;
        $defaultValue = true;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }
}
