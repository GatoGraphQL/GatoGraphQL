<?php

declare(strict_types=1);

namespace PoP\Root\Container\HybridCompilerPasses;

use PoP\Root\Container\CompilerPasses\AbstractCompilerPass;
use PoP\Root\Container\CompilerPasses\AutoconfigurableServicesCompilerPassTrait;
use PoP\Root\Container\ContainerBuilderWrapperInterface;
use PoP\Root\Instances\InstanceManagerInterface;
use PoP\Root\Services\BasicServiceInterface;

class SetInstanceManagerOnBasicServiceCompilerPass extends AbstractCompilerPass
{
    use AutoconfigurableServicesCompilerPassTrait;

    protected function doProcess(ContainerBuilderWrapperInterface $containerBuilderWrapper): void
    {
        $serviceClass = BasicServiceInterface::class;
        $definitions = $containerBuilderWrapper->getDefinitions();
        foreach ($definitions as $definitionID => $definition) {
            $definitionClass = $definition->getClass();
            if ($definitionClass === null || !is_a($definitionClass, $serviceClass, true)) {
                continue;
            }

            $onlyProcessAutoconfiguredServices = $this->onlyProcessAutoconfiguredServices();
            if (
                !$onlyProcessAutoconfiguredServices
                || $definition->isAutoconfigured()
            ) {
                $definition->addMethodCall(
                    'setInstanceManager',
                    [$this->createReference(InstanceManagerInterface::class)]
                );
            }
        }
    }
}
