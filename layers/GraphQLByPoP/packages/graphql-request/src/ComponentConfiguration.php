<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLRequest;

use PoP\BasicService\Component\AbstractComponentConfiguration;
use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    private bool $disableGraphQLAPIForPoP = false;

    public function disableGraphQLAPIForPoP(): bool
    {
        // Define properties
        $envVariable = Environment::DISABLE_GRAPHQL_API_FOR_POP;
        $selfProperty = &$this->disableGraphQLAPIForPoP;
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
