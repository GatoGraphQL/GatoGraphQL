<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLQuery;

use PoP\BasicService\Component\AbstractComponentConfiguration;
use PoP\BasicService\Component\EnvironmentValueHelpers;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    private bool $enableVariablesAsExpressions = false;

    public function enableVariablesAsExpressions(): bool
    {
        // Define properties
        $envVariable = Environment::ENABLE_VARIABLES_AS_EXPRESSIONS;
        $selfProperty = &$this->enableVariablesAsExpressions;
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
}
