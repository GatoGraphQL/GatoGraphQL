<?php

declare(strict_types=1);

namespace PoP\Root\Registries;

interface ServiceDefinitionIDRegistryInterface
{
    public function addServiceDefinitionID(string $serviceClass): void;
    /**
     * @return string[]
     */
    public function getServiceDefinitionIDs(): array;
}
