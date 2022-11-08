<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Rector\Downgrade\Configurators\ChainedRules;

use Rector\DowngradePhp74\Rector\ClassMethod\DowngradeCovariantReturnTypeRector;

/**
 * Covariants in Symfony Definition require 2 passes:
 *
 * - From: `protected function instantiateNode(): BooleanNode`
 * - To: `protected function instantiateNode(): ScalarNode`
 *
 * - From: `protected function instantiateNode(): ScalarNode`
 * - To: `protected function instantiateNode(): VariableNode`
 *
 * Because of chained rules not taking place, manually execute the 2nd rule
 */
abstract class AbstractCovariantReturnTypeChainedRuleContainerConfigurationService extends AbstractChainedRuleContainerConfigurationService
{
    protected function getRectorRuleClasses(): array
    {
        return [
            DowngradeCovariantReturnTypeRector::class,
        ];
    }
}
