<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Rector\Downgrade\Configurators\ChainedRules;

use PoP\PoP\Config\Rector\Downgrade\Configurators\GraphQLAPIContainerConfigurationServiceTrait;

class GraphQLAPICacheItemContainerConfigurationService extends AbstractPluginCacheItemContainerConfigurationService
{
    use GraphQLAPIContainerConfigurationServiceTrait;
    
    protected function getPaths(): array
    {
        return [
            $this->pluginDir . '/vendor/symfony/cache/CacheItem.php',
        ];
    }
}
