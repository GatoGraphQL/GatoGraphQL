<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Rector\Downgrade\Configurators\ChainedRules;

use PoP\PoP\Config\Rector\Downgrade\Configurators\GatoGraphQLContainerConfigurationServiceTrait;

class GatoGraphQLCovariantReturnTypeChainedRuleContainerConfigurationService extends AbstractPluginArrowFnUnionTypeChainedRuleContainerConfigurationService
{
    use GatoGraphQLContainerConfigurationServiceTrait;

    protected function getPaths(): array
    {
        return [
            $this->pluginDir . '/vendor/symfony/config/Definition/Builder/BooleanNodeDefinition.php',
            $this->pluginDir . '/vendor/symfony/config/Definition/Builder/EnumNodeDefinition.php',
            $this->pluginDir . '/vendor/symfony/config/Definition/Builder/FloatNodeDefinition.php',
            $this->pluginDir . '/vendor/symfony/config/Definition/Builder/IntegerNodeDefinition.php',
        ];
    }
}
