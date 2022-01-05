<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLRequest;

use PoP\BasicService\Component\AbstractComponentConfiguration;
use PoP\BasicService\Component\EnvironmentValueHelpers;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    public function disableGraphQLAPIForPoP(): bool
    {
        // Define properties
        $envVariable = Environment::DISABLE_GRAPHQL_API_FOR_POP;
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
