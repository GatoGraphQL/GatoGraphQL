<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLQuery;

use PoP\Root\Component\AbstractComponentConfiguration;
use PoP\Root\Component\EnvironmentValueHelpers;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    public function enableVariablesAsExpressions(): bool
    {
        $envVariable = Environment::ENABLE_VARIABLES_AS_EXPRESSIONS;
        $defaultValue = false;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }
}
