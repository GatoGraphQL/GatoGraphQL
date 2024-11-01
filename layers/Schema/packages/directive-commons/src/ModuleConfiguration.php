<?php

declare(strict_types=1);

namespace PoPSchema\DirectiveCommons;

use PoP\Root\Module\AbstractModuleConfiguration;
use PoP\Root\Module\EnvironmentValueHelpers;

class ModuleConfiguration extends AbstractModuleConfiguration
{
    public function skipBubblingUpErrorsInMetaDirectives(): bool
    {
        $envVariable = Environment::SKIP_BUBBLING_UP_ERRORS_IN_META_DIRECTIVES;
        $defaultValue = false;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }
}
