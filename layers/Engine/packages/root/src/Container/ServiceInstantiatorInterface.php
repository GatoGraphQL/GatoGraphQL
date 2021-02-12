<?php

declare(strict_types=1);

namespace PoP\Root\Container;

/**
 * Collect the services that must be automatically instantiated,
 * i.e. that no piece of code will explicitly reference, but whose
 * services must always be executed. Eg: hooks.
 */
interface ServiceInstantiatorInterface
{
    public function addServiceClass(string $sertiveClass): void;
    // public function getServiceClasses(): array;
    public function initializeServices(): void;
}
