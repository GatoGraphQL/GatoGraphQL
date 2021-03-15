<?php

declare(strict_types=1);

namespace PoP\Root\Container\CompilerPasses;

use PoP\Root\Container\ContainerBuilderWrapper;
use PoP\Root\Container\ContainerBuilderWrapperInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\ExpressionLanguage\Expression;

/**
 * This class enables to leak the implementation of Compiler Passes to the application.
 * This is needed to add compiler passes on "-wp" packages, which are not scoped
 * with PHP-Scoper. Then, in these packages we can't reference Symfony (or any 3rd party)
 */
abstract class AbstractCompilerPass implements CompilerPassInterface
{
    final public function process(ContainerBuilder $containerBuilder): void
    {
        if (!$this->enabled()) {
            return;
        }
        $this->doProcess(new ContainerBuilderWrapper($containerBuilder));
    }

    /**
     * Compiler passes must implement the logic in this function, not in `process`
     */
    abstract protected function doProcess(ContainerBuilderWrapperInterface $containerBuilderWrapper): void;

    protected function enabled(): bool
    {
        return true;
    }

    protected function createReference(string $id): Reference
    {
        return new Reference($id);
    }

    protected function createExpression(string $expression): Expression
    {
        return new Expression($expression);
    }
}
