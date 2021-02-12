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
    protected array $serviceClasses = [];

    public function addServiceClass(string $serviceClass): void
    {
        $this->serviceClasses[] = $serviceClass;
    }
    // public function getServiceClasses(): array
    // {
    //     return $this->serviceClasses;
    // }
    public function initializeServices(): void
    {
        $containerBuilder = ContainerBuilderFactory::getInstance();
        foreach ($this->serviceClasses as $serviceClass) {
            // Watch out! Not all services are instantiated under their own class!
            // Eg: GraphQLAPI\GraphQLAPI\Overrides\Services\Clients\GraphiQLClient
            // overrides another services, yet it implements this interface
            if ($containerBuilder->has($serviceClass)) {
                /** @var AutomaticallyInstantiatedServiceInterface */
                $service = $containerBuilder->get($serviceClass);
                $service->initialize();
            }
        }
    }
}
