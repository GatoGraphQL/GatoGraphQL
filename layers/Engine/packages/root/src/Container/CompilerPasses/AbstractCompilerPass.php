<?php

declare(strict_types=1);

namespace PoP\Root\Container\CompilerPasses;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

abstract class AbstractCompilerPass implements CompilerPassInterface
{
    final public function process(ContainerBuilder $containerBuilder): void
    {
        if (!$this->enabled()) {
            return;
        }
        $this->doProcess($containerBuilder);
    }

    abstract protected function doProcess(ContainerBuilder $containerBuilder): void;

    protected function enabled(): bool
    {
        return true;
    }
}
