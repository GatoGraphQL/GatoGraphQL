<?php

declare(strict_types=1);

namespace PoP\Root\Registries;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class CompilerPassRegistry implements CompilerPassRegistryInterface
{
    /**
     * @var CompilerPassInterface[]
     */
    protected array $compilerPasses = [];

    public function addCompilerPass(CompilerPassInterface $compilerPass): void
    {
        $this->compilerPasses[] = $compilerPass;
    }
    /**
     * @return CompilerPassInterface[]
     */
    public function getCompilerPasses(): array
    {
        return $this->compilerPasses;
    }
}
