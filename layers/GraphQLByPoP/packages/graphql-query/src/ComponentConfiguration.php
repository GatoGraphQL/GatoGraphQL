<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLQuery;

use PoP\Root\Module\AbstractModuleConfiguration;
use PoP\Root\Module\EnvironmentValueHelpers;

class ModuleConfiguration extends AbstractModuleConfiguration
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
