<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Rector\Downgrade\Configurators\ChainedRules;

use PoP\PoP\Extensions\Rector\DowngradePhp70\Rector\ClassMethod\DowngradeSelfTypeDeclarationRector;

/**
 * Hack to fix bug.
 *
 * The function in CacheItem should be downgraded as:
 *     function tag($tags);
 * But is downgraded as:
 *     function tag($tags): self;
 *
 * @see https://github.com/rectorphp/rector/issues/5962
 */
abstract class AbstractCacheItemChainedRuleContainerConfigurationService extends AbstractChainedRuleContainerConfigurationService
{
    protected function getRectorRuleClasses(): array
    {
        return [
            DowngradeSelfTypeDeclarationRector::class,
        ];
    }
}
