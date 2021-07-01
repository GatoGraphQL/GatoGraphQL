<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Rector\Downgrade\Configurators\ChainedRules;

class MonorepoCacheItemChainedRuleContainerConfigurationService extends AbstractCacheItemChainedRuleContainerConfigurationService
{
    protected function getPaths(): array
    {
        return [
            $this->rootDirectory . '/vendor/symfony/cache/CacheItem.php',
        ];
    }
}
