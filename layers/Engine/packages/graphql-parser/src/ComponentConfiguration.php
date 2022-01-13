<?php

declare(strict_types=1);

namespace PoP\GraphQLParser;

use PoP\Root\Component\AbstractComponentConfiguration;
use PoP\BasicService\Component\EnvironmentValueHelpers;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    public function enableMultipleQueryExecution(): bool
    {
        $envVariable = Environment::ENABLE_MULTIPLE_QUERY_EXECUTION;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    public function enableComposableDirectives(): bool
    {
        $envVariable = Environment::ENABLE_COMPOSABLE_DIRECTIVES;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }
}
