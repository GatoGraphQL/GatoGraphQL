<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer;

use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;
use PoP\ComponentModel\ComponentConfiguration\ComponentConfigurationTrait;

class ComponentConfiguration
{
    use ComponentConfigurationTrait;

    private static bool $addSelfFieldForRootTypeToSchema = false;
    private static bool $sortSchemaAlphabetically = true;
    private static bool $enableProactiveFeedback = true;
    private static bool $enableProactiveFeedbackDeprecations = true;
    private static bool $enableProactiveFeedbackNotices = true;
    private static bool $enableProactiveFeedbackTraces = true;
    private static bool $enableProactiveFeedbackLogs = true;
    private static bool $enableNestedMutations = false;
    private static ?bool $enableGraphQLIntrospection = null;

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

    public static function enableProactiveFeedbackDeprecations(): bool
    {
        // Define properties
        $envVariable = Environment::ENABLE_PROACTIVE_FEEDBACK_DEPRECATIONS;
        $selfProperty = &self::$enableProactiveFeedbackDeprecations;
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

    public static function enableProactiveFeedbackNotices(): bool
    {
        // Define properties
        $envVariable = Environment::ENABLE_PROACTIVE_FEEDBACK_NOTICES;
        $selfProperty = &self::$enableProactiveFeedbackNotices;
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

    public static function enableProactiveFeedbackTraces(): bool
    {
        // Define properties
        $envVariable = Environment::ENABLE_PROACTIVE_FEEDBACK_TRACES;
        $selfProperty = &self::$enableProactiveFeedbackTraces;
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

    public static function enableProactiveFeedbackLogs(): bool
    {
        // Define properties
        $envVariable = Environment::ENABLE_PROACTIVE_FEEDBACK_LOGS;
        $selfProperty = &self::$enableProactiveFeedbackLogs;
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
