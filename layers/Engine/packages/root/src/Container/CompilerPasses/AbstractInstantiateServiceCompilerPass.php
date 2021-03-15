<?php

declare(strict_types=1);

namespace PoP\Root\Container\CompilerPasses;

use PoP\Root\Container\ContainerBuilderWrapperInterface;
use PoP\Root\Container\ServiceInstantiatorInterface;
use Symfony\Component\DependencyInjection\Reference;

abstract class AbstractInstantiateServiceCompilerPass extends AbstractCompilerPass
{
    protected function doProcess(ContainerBuilderWrapperInterface $containerBuilder): void
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
                'addService',
                [new Reference($definitionID)]
            );
        }
    }

    abstract protected function getServiceClass(): string;
}
