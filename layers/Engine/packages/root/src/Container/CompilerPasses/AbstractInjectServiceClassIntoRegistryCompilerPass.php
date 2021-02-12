<?php

declare(strict_types=1);

namespace PoP\Root\Container\CompilerPasses;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

abstract class AbstractInjectServiceClassIntoRegistryCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $containerBuilder): void
    {
        if (!$this->enabled()) {
            return;
        }
        $registryDefinition = $containerBuilder->getDefinition($this->getRegistryServiceDefinition());
        $definitions = $containerBuilder->getDefinitions();
        foreach ($definitions as $definition) {
            $definitionClass = $definition->getClass();
            if ($definitionClass === null || !is_a($definitionClass, $this->getServiceClass(), true)) {
                continue;
            }

            // Register the service in the corresponding registry
            $registryDefinition->addMethodCall(
                $this->getRegistryMethodCallName(),
                [$definitionClass]
            );
        }
    }

    abstract protected function getRegistryServiceDefinition(): string;
    abstract protected function getServiceClass(): string;
    abstract protected function getRegistryMethodCallName(): string;

    protected function enabled(): bool
    {
        return true;
    }
}
