<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Rector\Downgrade\Configurators\ChainedRules;

use PoP\PoP\Config\Rector\Downgrade\Configurators\GatoGraphQLContainerConfigurationServiceTrait;

class GatoGraphQLCacheItemChainedRuleContainerConfigurationService extends AbstractPluginCacheItemChainedRuleContainerConfigurationService
{
    use GatoGraphQLContainerConfigurationServiceTrait;

    protected function getPaths(): array
    {
        return [
            $this->pluginDir . '/vendor/symfony/cache/CacheItem.php',
        ];
    }
}
