<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLRequest;

use PoP\BasicService\Component\AbstractComponentConfiguration;
use PoP\BasicService\Component\EnvironmentValueHelpers;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    public function disableGraphQLAPIForPoP(): bool
    {
        $envVariable = Environment::DISABLE_GRAPHQL_API_FOR_POP;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }
}
