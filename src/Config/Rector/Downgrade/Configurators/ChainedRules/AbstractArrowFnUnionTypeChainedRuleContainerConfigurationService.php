<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Rector\Downgrade\Configurators\ChainedRules;

use Rector\DowngradePhp80\Rector\FunctionLike\DowngradeUnionTypeDeclarationRector;

/**
 * Hack to fix bug.
 *
 * `fn(int|string $foo)` requires 2 steps to be downgraded:
 *
 *   1. function(int|string $foo)
 *   2. function($foo)
 *
 * Because of chained rules not taking place, manually execute the 2nd rule
 */
abstract class AbstractArrowFnUnionTypeChainedRuleContainerConfigurationService extends AbstractChainedRuleContainerConfigurationService
{
    protected function getRectorRuleClasses(): array
    {
        return [
            DowngradeUnionTypeDeclarationRector::class,
        ];
    }
}
