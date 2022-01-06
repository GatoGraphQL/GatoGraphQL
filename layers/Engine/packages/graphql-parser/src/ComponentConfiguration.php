<?php

declare(strict_types=1);

namespace PoP\GraphQLParser;

use PoP\BasicService\Component\AbstractComponentConfiguration;
use PoP\BasicService\Component\EnvironmentValueHelpers;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    /**
     * Disable hook, because it is invoked by `export-directive`
     * on its Component's `resolveEnabled` function.
     */
    public function enableMultipleQueryExecution(): bool
    {
        // Define properties
        $envVariable = Environment::ENABLE_MULTIPLE_QUERY_EXECUTION;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment
        $this->getConfigurationValueFromEnvVariable(
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
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        $this->getConfigurationValueFromEnvVariable(
            $envVariable,
            $defaultValue,
            $callback,
        );
        return $this->configuration[$envVariable];
    }
}
