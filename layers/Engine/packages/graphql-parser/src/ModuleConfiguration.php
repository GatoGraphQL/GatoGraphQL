<?php

declare(strict_types=1);

namespace PoP\GraphQLParser;

use PoP\Root\Module\AbstractModuleConfiguration;
use PoP\Root\Module\EnvironmentValueHelpers;

class ModuleConfiguration extends AbstractModuleConfiguration
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

    public function enableMultiFieldDirectives(): bool
    {
        $envVariable = Environment::ENABLE_MULTIFIELD_DIRECTIVES;
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

    public function enableObjectResolvedFieldValueReferences(): bool
    {
        $envVariable = Environment::ENABLE_RESOLVED_FIELD_VARIABLE_REFERENCES;
        $defaultValue = false;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    public function useLastOperationInDocumentForMultipleQueryExecutionWhenOperationNameNotProvided(): bool
    {
        $envVariable = Environment::USE_LAST_OPERATION_IN_DOCUMENT_FOR_MULTIPLE_QUERY_EXECUTION_WHEN_OPERATION_NAME_NOT_PROVIDED;
        $defaultValue = false;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }
}
