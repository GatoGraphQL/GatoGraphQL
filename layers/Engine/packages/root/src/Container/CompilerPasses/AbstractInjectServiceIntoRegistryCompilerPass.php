<?php

declare(strict_types=1);

namespace PoP\Root\Container\CompilerPasses;

use PoP\Root\Container\CompilerPassContainerInterface;
use Symfony\Component\DependencyInjection\Reference;

abstract class AbstractInjectServiceIntoRegistryCompilerPass extends AbstractCompilerPass
{
    protected function doProcess(CompilerPassContainerInterface $containerBuilder): void
    {
        $registryDefinition = $containerBuilder->getDefinition($this->getRegistryServiceDefinition());
        $definitions = $containerBuilder->getDefinitions();
        $serviceClass = $this->getServiceClass();
        foreach ($definitions as $definitionID => $definition) {
            $definitionClass = $definition->getClass();
            if (
                $definitionClass === null
                || !is_a(
                    $definitionClass,
                    $serviceClass,
                    true
                )
            ) {
                continue;
            }

            // Register the service in the corresponding registry
            $registryDefinition->addMethodCall(
                $this->getRegistryMethodCallName(),
                [new Reference($definitionID)]
            );
        }
    }

    abstract protected function getRegistryServiceDefinition(): string;
    abstract protected function getServiceClass(): string;
    abstract protected function getRegistryMethodCallName(): string;
}
