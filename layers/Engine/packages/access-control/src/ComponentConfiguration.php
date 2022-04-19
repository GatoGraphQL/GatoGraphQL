<?php

declare(strict_types=1);

namespace PoP\AccessControl;

use PoP\Root\Component\AbstractComponentConfiguration;
use PoP\Root\Component\EnvironmentValueHelpers;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    public function usePrivateSchemaMode(): bool
    {
        $envVariable = Environment::USE_PRIVATE_SCHEMA_MODE;
        $defaultValue = false;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    public function enableIndividualControlForPublicPrivateSchemaMode(): bool
    {
        $envVariable = Environment::ENABLE_INDIVIDUAL_CONTROL_FOR_PUBLIC_PRIVATE_SCHEMA_MODE;
        $defaultValue = true;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    /**
     * If either constant `USE_PRIVATE_SCHEMA_MODE` or `ENABLE_INDIVIDUAL_CONTROL_FOR_PUBLIC_PRIVATE_SCHEMA_MODE`
     * (which enables to define the private schema mode for a specific entry) is true,
     * then the schema (as obtained by querying the "__schema" field) is dynamic:
     * Fields will be available or not depending on the user being logged in or not
     */
    public function canSchemaBePrivate(): bool
    {
        return
            $this->enableIndividualControlForPublicPrivateSchemaMode()
            || $this->usePrivateSchemaMode();
    }
}
