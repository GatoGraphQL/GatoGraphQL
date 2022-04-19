<?php

declare(strict_types=1);

namespace PoP\GraphQLParser;

use PoP\Root\Component\AbstractComponentConfiguration;
use PoP\Root\Component\EnvironmentValueHelpers;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    public function enableMultipleQueryExecution(): bool
    {
        $envVariable = Environment::ENABLE_MULTIPLE_QUERY_EXECUTION;
        $defaultValue = false;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    public function enableComposableDirectives(): bool
    {
        $envVariable = Environment::ENABLE_COMPOSABLE_DIRECTIVES;
        $defaultValue = false;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    public function enableDynamicVariables(): bool
    {
        $envVariable = Environment::ENABLE_DYNAMIC_VARIABLES;
        $defaultValue = false;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    public function enableResolvedFieldVariableReferences(): bool
    {
        if (!$this->enableDynamicVariables()) {
            return false;
        }

        $envVariable = Environment::ENABLE_RESOLVED_FIELD_VARIABLE_REFERENCES;
        $defaultValue = false;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }
}
