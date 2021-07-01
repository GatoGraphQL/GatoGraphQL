<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Rector\Downgrade\Configurators\ChainedRules;

class MonorepoArrowFnMixedTypeChainedRuleContainerConfigurationService extends AbstractArrowFnMixedTypeChainedRuleContainerConfigurationService
{
    protected function getPaths(): array
    {
        return [
            $this->rootDirectory . '/layers/Engine/packages/component-model/src/Schema/FieldQueryInterpreter.php',
            $this->rootDirectory . '/layers/API/packages/api/src/Schema/FieldQueryConvertor.php',
        ];
    }
}
