<?php

declare(strict_types=1);

namespace PoP\GraphQLParser;

use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;

class ComponentConfiguration extends \PoP\BasicService\Component\AbstractComponentConfiguration
{
    private static bool $enableMultipleQueryExecution = false;
    private static bool $enableComposableDirectives = false;

    /**
     * Disable hook, because it is invoked by `export-directive`
     * on its Component's `resolveEnabled` function.
     */
    public static function enableMultipleQueryExecution(): bool
    {
        // Define properties
        $envVariable = Environment::ENABLE_MULTIPLE_QUERY_EXECUTION;
        $selfProperty = &self::$enableMultipleQueryExecution;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment
        self::maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    public static function enableComposableDirectives(): bool
    {
        // Define properties
        $envVariable = Environment::ENABLE_COMPOSABLE_DIRECTIVES;
        $selfProperty = &self::$enableComposableDirectives;
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
}
