<?php

declare(strict_types=1);

namespace PoP\Root\Container;

use PoP\Root\Services\AutomaticallyInstantiatedServiceInterface;

/**
 * Collect the services that must be automatically instantiated,
 * i.e. that no piece of code will explicitly reference, but whose
 * services must always be executed. Eg: hooks.
 */
class ServiceInstantiator implements ServiceInstantiatorInterface
{
    /**
     * @var string[]
     */
    protected array $serviceDefinitions = [];

    public function addServiceDefinition(string $serviceDefinition): void
    {
        $this->serviceDefinitions[] = $serviceDefinition;
    }
    public function initializeServices(): void
    {
        $containerBuilder = ContainerBuilderFactory::getInstance();
        foreach ($this->serviceDefinitions as $serviceDefinition) {
            /** @var AutomaticallyInstantiatedServiceInterface */
            $service = $containerBuilder->get($serviceDefinition);
            $service->initialize();
        }
    }
}
