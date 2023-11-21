<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Rector\Downgrade\Configurators\ChainedRules;

use PoP\PoP\Extensions\Rector\EarlyReturn\Rector\If_\ChangeIfOrReturnToEarlyReturnRector;
use Rector\Core\Contract\Rector\RectorInterface;

abstract class AbstractChangeIfOrReturnToEarlyReturnChainedRuleContainerConfigurationService extends AbstractChainedRuleContainerConfigurationService
{
    /**
     * @return string[]
     * @phpstan-return array<class-string<RectorInterface>>
     */
    protected function getRectorRuleClasses(): array
    {
        return [
            ChangeIfOrReturnToEarlyReturnRector::class,
        ];
    }
}
