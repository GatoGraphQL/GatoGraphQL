<?php

declare(strict_types=1);

namespace PoP\API;

use PoP\Root\App;
use PoP\Root\Component\AbstractComponentConfiguration;
use PoP\ComponentModel\Component as ComponentModelComponent;
use PoP\ComponentModel\ComponentConfiguration as ComponentModelComponentConfiguration;
use PoP\BasicService\Component\EnvironmentValueHelpers;

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
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

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
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

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
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    public function enableMutations(): bool
    {
        $envVariable = Environment::ENABLE_MUTATIONS;
        $defaultValue = true;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    /**
     * Remove unwanted data added to the REQUEST_URI, replacing
     * it with the website home URL.
     * Eg: the language information from qTranslate (https://domain.com/en/...)
     */
    public function overrideRequestURI(): bool
    {
        $envVariable = Environment::OVERRIDE_REQUEST_URI;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

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
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

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
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }
}
