<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer;

use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;
use PoP\ComponentModel\ComponentConfiguration\ComponentConfigurationTrait;

class ComponentConfiguration
{
    use ComponentConfigurationTrait;

    private static $addSelfFieldForRootTypeToSchema;
    private static $sortSchemaAlphabetically;
    private static $enableRemoveIfNullDirective;
    private static $enableProactiveFeedback;
    private static $enableNestedMutations;
    private static $enableGraphQLIntrospection;

    public static function addSelfFieldForRootTypeToSchema(): bool
    {
        // Define properties
        $envVariable = Environment::ADD_SELF_FIELD_FOR_ROOT_TYPE_TO_SCHEMA;
        $selfProperty = &self::$addSelfFieldForRootTypeToSchema;
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

    public static function sortSchemaAlphabetically(): bool
    {
        // Define properties
        $envVariable = Environment::SORT_SCHEMA_ALPHABETICALLY;
        $selfProperty = &self::$sortSchemaAlphabetically;
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

    public static function enableRemoveIfNullDirective(): bool
    {
        // Define properties
        $envVariable = Environment::ENABLE_REMOVE_IF_NULL_DIRECTIVE;
        $selfProperty = &self::$enableRemoveIfNullDirective;
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

    public static function enableProactiveFeedback(): bool
    {
        // Define properties
        $envVariable = Environment::ENABLE_PROACTIVE_FEEDBACK;
        $selfProperty = &self::$enableProactiveFeedback;
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

    public static function enableNestedMutations(): bool
    {
        // Define properties
        $envVariable = Environment::ENABLE_NESTED_MUTATIONS;
        $selfProperty = &self::$enableNestedMutations;
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

    public static function enableGraphQLIntrospection(): ?bool
    {
        // Define properties
        $envVariable = Environment::ENABLE_GRAPHQL_INTROSPECTION;
        $selfProperty = &self::$enableGraphQLIntrospection;
        $defaultValue = null;
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
