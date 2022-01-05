<?php

declare(strict_types=1);

namespace PoP\GraphQLParser;

use PoP\BasicService\Component\AbstractComponentConfiguration;
use PoP\BasicService\Component\EnvironmentValueHelpers;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    private bool $enableMultipleQueryExecution = false;
    private bool $enableComposableDirectives = false;

    /**
     * Disable hook, because it is invoked by `export-directive`
     * on its Component's `resolveEnabled` function.
     */
    public function enableMultipleQueryExecution(): bool
    {
        // Define properties
        $envVariable = Environment::ENABLE_MULTIPLE_QUERY_EXECUTION;
        $selfProperty = &$this->enableMultipleQueryExecution;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $defaultValue,
            $callback,
        );
        return $this->configuration[$envVariable];
    }

    public function enableComposableDirectives(): bool
    {
        // Define properties
        $envVariable = Environment::ENABLE_COMPOSABLE_DIRECTIVES;
        $selfProperty = &$this->enableComposableDirectives;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $defaultValue,
            $callback,
        );
        return $this->configuration[$envVariable];
    }
}
