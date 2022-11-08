<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Rector\Downgrade\Configurators\ChainedRules;

class MonorepoCovariantChainedRuleContainerConfigurationService extends AbstractCovariantChainedRuleContainerConfigurationService
{
    protected function getPaths(): array
    {
        return [
            $this->rootDirectory . '/vendor/symfony/config/Definition/Builder/BooleanNodeDefinition.php',
            $this->rootDirectory . '/vendor/symfony/config/Definition/Builder/EnumNodeDefinition.php',
            $this->rootDirectory . '/vendor/symfony/config/Definition/Builder/FloatNodeDefinition.php',
            $this->rootDirectory . '/vendor/symfony/config/Definition/Builder/IntegerNodeDefinition.php',
        ];
    }
}
