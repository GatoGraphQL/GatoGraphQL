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
     * @var AutomaticallyInstantiatedServiceInterface[]
     */
    protected array $services = [];

    public function addService(AutomaticallyInstantiatedServiceInterface $service): void
    {
        $this->services[] = $service;
    }
    public function initializeServices(string $event): void
    {
        /**
         * Filter all the services that must be instantiated during the passed event
         */
        $servicesForEvent = array_filter(
            $this->services,
            fn ($service) => $service->getInstantiationEvent() == $event
        );
        foreach ($servicesForEvent as $service) {
            $service->initialize();
        }
    }
}
