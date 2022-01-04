<?php

declare(strict_types=1);

namespace PoP\API;

use PoP\BasicService\Component\AbstractComponentConfiguration;
use PoP\Root\Managers\ComponentManager;
use PoP\ComponentModel\Component as ComponentModelComponent;
use PoP\ComponentModel\ComponentConfiguration as ComponentModelComponentConfiguration;
use PoP\BasicService\Component\EnvironmentValueHelpers;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    private bool $useSchemaDefinitionCache = false;
    private bool $executeQueryBatchInStrictOrder = true;
    private bool $enableEmbeddableFields = false;
    private bool $enableMutations = true;
    private bool $overrideRequestURI = false;
    private bool $skipExposingGlobalFieldsInFullSchema = false;
    private bool $sortFullSchemaAlphabetically = true;

    public function useSchemaDefinitionCache(): bool
    {
        // First check that the Component Model cache is enabled
        /** @var ComponentModelComponentConfiguration */
        $componentConfiguration = ComponentManager::getComponent(ComponentModelComponent::class)->getConfiguration();
        if (!$componentConfiguration->useComponentModelCache()) {
            return false;
        }

        // Define properties
        $envVariable = Environment::USE_SCHEMA_DEFINITION_CACHE;
        $selfProperty = &$this->useSchemaDefinitionCache;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    public function executeQueryBatchInStrictOrder(): bool
    {
        // Define properties
        $envVariable = Environment::EXECUTE_QUERY_BATCH_IN_STRICT_ORDER;
        $selfProperty = &$this->executeQueryBatchInStrictOrder;
        $defaultValue = true;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    public function enableEmbeddableFields(): bool
    {
        // Define properties
        $envVariable = Environment::ENABLE_EMBEDDABLE_FIELDS;
        $selfProperty = &$this->enableEmbeddableFields;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    public function enableMutations(): bool
    {
        // Define properties
        $envVariable = Environment::ENABLE_MUTATIONS;
        $selfProperty = &$this->enableMutations;
        $defaultValue = true;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    /**
     * Remove unwanted data added to the REQUEST_URI, replacing
     * it with the website home URL.
     * Eg: the language information from qTranslate (https://domain.com/en/...)
     */
    public function overrideRequestURI(): bool
    {
        // Define properties
        $envVariable = Environment::OVERRIDE_REQUEST_URI;
        $selfProperty = &$this->overrideRequestURI;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    public function skipExposingGlobalFieldsInFullSchema(): bool
    {
        // Define properties
        $envVariable = Environment::SKIP_EXPOSING_GLOBAL_FIELDS_IN_FULL_SCHEMA;
        $selfProperty = &$this->skipExposingGlobalFieldsInFullSchema;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    public function sortFullSchemaAlphabetically(): bool
    {
        // Define properties
        $envVariable = Environment::SORT_FULL_SCHEMA_ALPHABETICALLY;
        $selfProperty = &$this->sortFullSchemaAlphabetically;
        $defaultValue = true;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }
}
