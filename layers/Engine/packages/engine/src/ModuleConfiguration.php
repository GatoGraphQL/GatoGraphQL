<?php

declare(strict_types=1);

namespace PoP\Engine;

use PoP\Root\Module\AbstractModuleConfiguration;
use PoP\Root\Module\EnvironmentValueHelpers;

class ModuleConfiguration extends AbstractModuleConfiguration
{
    public function disableRedundantRootTypeMutationFields(): bool
    {
        $envVariable = Environment::DISABLE_REDUNDANT_ROOT_TYPE_MUTATION_FIELDS;
        $defaultValue = false;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    /**
     * Enable fields "_appState", "_appStateKeys" and "_appStateValue"
     *
     * Enable only on DEV as to test the application,
     * and only when passing ?actions[]=enable-app-state-fields
     */
    public function enableQueryingAppStateFields(): bool
    {
        $envVariable = Environment::ENABLE_QUERYING_APP_STATE_FIELDS;
        $defaultValue = false;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }
}
