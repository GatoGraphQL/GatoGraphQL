<?php

declare(strict_types=1);

namespace PoPAPI\API;

use PoP\Root\App;
use PoP\Root\Component\AbstractComponentConfiguration;
use PoP\ComponentModel\Component as ComponentModelComponent;
use PoP\ComponentModel\ComponentConfiguration as ComponentModelComponentConfiguration;
use PoP\Root\Component\EnvironmentValueHelpers;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    public function useSchemaDefinitionCache(): bool
    {
        // First check that the Component Model cache is enabled
        /** @var ComponentModelComponentConfiguration */
        $componentConfiguration = App::getComponent(ComponentModelComponent::class)->getConfiguration();
        if (!$componentConfiguration->useComponentModelCache()) {
            return false;
        }

        $envVariable = Environment::USE_SCHEMA_DEFINITION_CACHE;
        $defaultValue = false;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    public function executeQueryBatchInStrictOrder(): bool
    {
        $envVariable = Environment::EXECUTE_QUERY_BATCH_IN_STRICT_ORDER;
        $defaultValue = true;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    public function enableEmbeddableFields(): bool
    {
        $envVariable = Environment::ENABLE_EMBEDDABLE_FIELDS;
        $defaultValue = false;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    public function skipExposingGlobalFieldsInFullSchema(): bool
    {
        $envVariable = Environment::SKIP_EXPOSING_GLOBAL_FIELDS_IN_FULL_SCHEMA;
        $defaultValue = false;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    public function sortFullSchemaAlphabetically(): bool
    {
        $envVariable = Environment::SORT_FULL_SCHEMA_ALPHABETICALLY;
        $defaultValue = true;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    public function addFullSchemaFieldToSchema(): bool
    {
        $envVariable = Environment::ADD_FULLSCHEMA_FIELD_TO_SCHEMA;
        $defaultValue = false;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }
}
