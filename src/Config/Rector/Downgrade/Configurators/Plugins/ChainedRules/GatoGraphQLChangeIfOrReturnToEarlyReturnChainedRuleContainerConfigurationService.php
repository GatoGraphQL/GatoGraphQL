<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Rector\Downgrade\Configurators\Plugins\ChainedRules;

use PoP\PoP\Config\Rector\Downgrade\Configurators\ChainedRules\AbstractPluginChangeIfOrReturnToEarlyReturnChainedRuleContainerConfigurationService;
use PoP\PoP\Config\Rector\Downgrade\Configurators\Plugins\GatoGraphQLContainerConfigurationServiceTrait;

class GatoGraphQLChangeIfOrReturnToEarlyReturnChainedRuleContainerConfigurationService extends AbstractPluginChangeIfOrReturnToEarlyReturnChainedRuleContainerConfigurationService
{
    use GatoGraphQLContainerConfigurationServiceTrait;

    protected function getPaths(): array
    {
        return [
            $this->pluginDir . '/vendor/symfony/cache/CacheItem.php',
        ];
    }
}
