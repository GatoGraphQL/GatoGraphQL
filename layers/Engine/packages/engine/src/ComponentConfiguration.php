<?php

declare(strict_types=1);

namespace PoP\Engine;

use PoP\BasicService\Component\AbstractComponentConfiguration;
use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    private bool $disableRedundantRootTypeMutationFields = false;
    private bool $enablePassingExpressionsByArgInNestedDirectives = true;

    public function disableRedundantRootTypeMutationFields(): bool
    {
        // Define properties
        $envVariable = Environment::DISABLE_REDUNDANT_ROOT_TYPE_MUTATION_FIELDS;
        $selfProperty = &$this->disableRedundantRootTypeMutationFields;
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

    public function enablePassingExpressionsByArgInNestedDirectives(): bool
    {
        // Define properties
        $envVariable = Environment::ENABLE_PASSING_EXPRESSIONS_BY_ARG_IN_NESTED_DIRECTIVES;
        $selfProperty = &$this->enablePassingExpressionsByArgInNestedDirectives;
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
