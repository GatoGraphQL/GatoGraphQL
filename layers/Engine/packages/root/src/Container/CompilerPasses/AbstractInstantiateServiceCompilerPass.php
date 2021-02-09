<?php

declare(strict_types=1);

namespace PoP\Root\Container\CompilerPasses;

use PoP\Root\Container\ContainerServiceStore;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

abstract class AbstractInstantiateServiceCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $containerBuilder): void
    {
        $serviceClass = $this->getServiceClass();
        $definitions = $containerBuilder->getDefinitions();
        foreach ($definitions as $definition) {
            $definitionClass = $definition->getClass();
            if ($definitionClass === null || !is_a($definitionClass, $serviceClass, true)) {
                continue;
            }

            ContainerServiceStore::addServiceToInstantiate($definition->getClass());
        }
    }

    abstract protected function getServiceClass(): string;
}
