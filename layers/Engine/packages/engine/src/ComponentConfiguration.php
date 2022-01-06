<?php

declare(strict_types=1);

namespace PoP\Engine;

use PoP\BasicService\Component\AbstractComponentConfiguration;
use PoP\BasicService\Component\EnvironmentValueHelpers;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    public function disableRedundantRootTypeMutationFields(): bool
    {
        // Define properties
        $envVariable = Environment::DISABLE_REDUNDANT_ROOT_TYPE_MUTATION_FIELDS;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
        return $this->configuration[$envVariable];
    }

    public function enablePassingExpressionsByArgInNestedDirectives(): bool
    {
        // Define properties
        $envVariable = Environment::ENABLE_PASSING_EXPRESSIONS_BY_ARG_IN_NESTED_DIRECTIVES;
        $defaultValue = true;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
        return $this->configuration[$envVariable];
    }
}
