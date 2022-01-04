<?php

declare(strict_types=1);

namespace PoP\API;

use PoP\ComponentModel\ComponentConfiguration as ComponentModelComponentConfiguration;
use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;

class ComponentConfiguration extends \PoP\BasicService\Component\AbstractComponentConfiguration
{
    private static bool $useSchemaDefinitionCache = false;
    private static bool $executeQueryBatchInStrictOrder = true;
    private static bool $enableEmbeddableFields = false;
    private static bool $enableMutations = true;
    private static bool $overrideRequestURI = false;
    private static bool $skipExposingGlobalFieldsInFullSchema = false;
    private static bool $sortFullSchemaAlphabetically = true;

    public static function useSchemaDefinitionCache(): bool
    {
        // First check that the Component Model cache is enabled
        if (!ComponentModelComponentConfiguration::useComponentModelCache()) {
            return false;
        }

        // Define properties
        $envVariable = Environment::USE_SCHEMA_DEFINITION_CACHE;
        $selfProperty = &self::$useSchemaDefinitionCache;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    public static function executeQueryBatchInStrictOrder(): bool
    {
        // Define properties
        $envVariable = Environment::EXECUTE_QUERY_BATCH_IN_STRICT_ORDER;
        $selfProperty = &self::$executeQueryBatchInStrictOrder;
        $defaultValue = true;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    public static function enableEmbeddableFields(): bool
    {
        // Define properties
        $envVariable = Environment::ENABLE_EMBEDDABLE_FIELDS;
        $selfProperty = &self::$enableEmbeddableFields;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    public static function enableMutations(): bool
    {
        // Define properties
        $envVariable = Environment::ENABLE_MUTATIONS;
        $selfProperty = &self::$enableMutations;
        $defaultValue = true;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue(
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
    public static function overrideRequestURI(): bool
    {
        // Define properties
        $envVariable = Environment::OVERRIDE_REQUEST_URI;
        $selfProperty = &self::$overrideRequestURI;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    public static function skipExposingGlobalFieldsInFullSchema(): bool
    {
        // Define properties
        $envVariable = Environment::SKIP_EXPOSING_GLOBAL_FIELDS_IN_FULL_SCHEMA;
        $selfProperty = &self::$skipExposingGlobalFieldsInFullSchema;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    public static function sortFullSchemaAlphabetically(): bool
    {
        // Define properties
        $envVariable = Environment::SORT_FULL_SCHEMA_ALPHABETICALLY;
        $selfProperty = &self::$sortFullSchemaAlphabetically;
        $defaultValue = true;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }
}
