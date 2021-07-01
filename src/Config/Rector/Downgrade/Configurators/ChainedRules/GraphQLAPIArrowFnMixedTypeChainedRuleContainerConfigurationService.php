<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Rector\Downgrade\Configurators\ChainedRules;

use PoP\PoP\Config\Rector\Downgrade\Configurators\GraphQLAPIContainerConfigurationServiceTrait;

class GraphQLAPIArrowFnMixedTypeChainedRuleContainerConfigurationService extends AbstractPluginCacheItemChainedRuleContainerConfigurationService
{
    use GraphQLAPIContainerConfigurationServiceTrait;
    
    protected function getPaths(): array
    {
        return [
            $this->pluginDir . '/vendor/getpop/component-model/src/Schema/FieldQueryInterpreter.php',
            $this->pluginDir . '/vendor/getpop/api/src/Schema/FieldQueryConvertor.php',
        ];
    }
}
