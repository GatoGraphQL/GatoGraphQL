<?php

declare(strict_types=1);

namespace PoPWPSchema\Blocks;

use PoP\Root\Module\AbstractModuleConfiguration;
use PoP\Root\Module\EnvironmentValueHelpers;

class ModuleConfiguration extends AbstractModuleConfiguration
{
    public function useSingleTypeInsteadOfBlockUnionType(): bool
    {
        $envVariable = Environment::USE_SINGLE_TYPE_INSTEAD_OF_BLOCK_UNION_TYPE;
        $defaultValue = false;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    public function treatBlockTypesAsSensitiveData(): bool
    {
        $envVariable = Environment::TREAT_BLOCK_TYPES_AS_SENSITIVE_DATA;
        $defaultValue = true;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }
}
