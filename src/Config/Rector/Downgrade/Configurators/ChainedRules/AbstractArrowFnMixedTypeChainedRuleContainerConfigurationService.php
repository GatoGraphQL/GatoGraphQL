<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Rector\Downgrade\Configurators\ChainedRules;

use Rector\Core\Contract\Rector\RectorInterface;
use Rector\DowngradePhp80\Rector\FunctionLike\DowngradeMixedTypeDeclarationRector;

/**
 * Hack to fix bug.
 *
 * `fn(mixed $foo)` requires 2 steps to be downgraded:
 *
 *   1. function(mixed $foo)
 *   2. function($foo)
 *
 * Because of chained rules not taking place, manually execute the 2nd rule
 */
abstract class AbstractArrowFnMixedTypeChainedRuleContainerConfigurationService extends AbstractChainedRuleContainerConfigurationService
{
    /**
     * @return string[]
     * @phpstan-return array<class-string<RectorInterface>>
     */
    protected function getRectorRuleClasses(): array
    {
        return [
            DowngradeMixedTypeDeclarationRector::class,
        ];
    }
}
