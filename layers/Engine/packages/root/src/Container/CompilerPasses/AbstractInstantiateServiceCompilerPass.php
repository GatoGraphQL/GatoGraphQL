<?php

declare(strict_types=1);

namespace PoP\Root\Container\CompilerPasses;

use PoP\Root\Container\ServiceInstantiatorInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

abstract class AbstractInstantiateServiceCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $containerBuilder): void
    {
        $serviceInstantiatorDefinition = $containerBuilder->getDefinition(ServiceInstantiatorInterface::class);
        $serviceClass = $this->getServiceClass();
        $definitions = $containerBuilder->getDefinitions();
        foreach ($definitions as $definitionID => $definition) {
            $definitionClass = $definition->getClass();
            if ($definitionClass === null || !is_a($definitionClass, $serviceClass, true)) {
                continue;
            }

            $serviceInstantiatorDefinition->addMethodCall(
                'addServiceDefinition',
                [$definitionID]
            );
        }
    }

    abstract protected function getServiceClass(): string;
}
