<?php

declare(strict_types=1);

namespace PoPAPI\API;

use PoP\Root\App;
use PoP\Root\Module\AbstractModuleConfiguration;
use PoP\ComponentModel\Module as ComponentModelModule;
use PoP\ComponentModel\ModuleConfiguration as ComponentModelModuleConfiguration;
use PoP\Root\Module\EnvironmentValueHelpers;

class ModuleConfiguration extends AbstractModuleConfiguration
{
    public function useSchemaDefinitionCache(): bool
    {
        // First check that the Component Model cache is enabled
        /** @var ComponentModelModuleConfiguration */
        $moduleConfiguration = App::getModule(ComponentModelModule::class)->getConfiguration();
        if (!$moduleConfiguration->enableComponentModelCache()) {
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
