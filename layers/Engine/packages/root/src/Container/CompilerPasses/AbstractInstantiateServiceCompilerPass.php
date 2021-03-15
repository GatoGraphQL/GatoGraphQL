<?php

declare(strict_types=1);

namespace PoP\Root\Container\CompilerPasses;

use PoP\Root\Container\ContainerBuilderWrapperInterface;
use PoP\Root\Container\ServiceInstantiatorInterface;

abstract class AbstractInstantiateServiceCompilerPass extends AbstractCompilerPass
{
    protected function doProcess(ContainerBuilderWrapperInterface $containerBuilderWrapper): void
    {
        $serviceInstantiatorDefinition = $containerBuilderWrapper->getDefinition(ServiceInstantiatorInterface::class);
        $serviceClass = $this->getServiceClass();
        $definitions = $containerBuilderWrapper->getDefinitions();
        foreach ($definitions as $definitionID => $definition) {
            $definitionClass = $definition->getClass();
            if ($definitionClass === null || !is_a($definitionClass, $serviceClass, true)) {
                continue;
            }

            $serviceInstantiatorDefinition->addMethodCall(
                'addService',
                [$this->createReference($definitionID)]
            );
        }
    }

    abstract protected function getServiceClass(): string;
}
