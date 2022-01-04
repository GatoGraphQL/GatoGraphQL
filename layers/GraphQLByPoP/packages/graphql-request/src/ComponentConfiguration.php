<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLRequest;

use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;

class ComponentConfiguration extends \PoP\BasicService\Component\AbstractComponentConfiguration
{
    private static bool $disableGraphQLAPIForPoP = false;

    public static function disableGraphQLAPIForPoP(): bool
    {
        // Define properties
        $envVariable = Environment::DISABLE_GRAPHQL_API_FOR_POP;
        $selfProperty = &self::$disableGraphQLAPIForPoP;
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
