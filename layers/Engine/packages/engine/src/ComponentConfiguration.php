<?php

declare(strict_types=1);

namespace PoP\Engine;

use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;

class ComponentConfiguration extends \PoP\BasicService\Component\AbstractComponentConfiguration
{
    private static bool $disableRedundantRootTypeMutationFields = false;
    private static bool $enablePassingExpressionsByArgInNestedDirectives = true;

    public static function disableRedundantRootTypeMutationFields(): bool
    {
        // Define properties
        $envVariable = Environment::DISABLE_REDUNDANT_ROOT_TYPE_MUTATION_FIELDS;
        $selfProperty = &self::$disableRedundantRootTypeMutationFields;
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

    public static function enablePassingExpressionsByArgInNestedDirectives(): bool
    {
        // Define properties
        $envVariable = Environment::ENABLE_PASSING_EXPRESSIONS_BY_ARG_IN_NESTED_DIRECTIVES;
        $selfProperty = &self::$enablePassingExpressionsByArgInNestedDirectives;
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
