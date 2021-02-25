<?php

declare(strict_types=1);

namespace PoP\Root\Container;

use PoP\Root\Services\AutomaticallyInstantiatedServiceInterface;

/**
 * Collect the services that must be automatically instantiated,
 * i.e. that no piece of code will explicitly reference, but whose
 * services must always be executed. Eg: hooks.
 */
interface ServiceInstantiatorInterface
{
    public function addService(AutomaticallyInstantiatedServiceInterface $service): void;
    public function initializeServices(string $event): void;
}
