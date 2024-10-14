<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Registries;

use GatoGraphQL\GatoGraphQL\Services\EndpointExecuters\EndpointExecuterInterface;

interface EndpointExecuterRegistryInterface
{
    public function addEndpointExecuter(EndpointExecuterInterface $endpointExecuter): void;
    /**
     * @return EndpointExecuterInterface[]
     */
    public function getEndpointExecuters(): array;
    /**
     * @return EndpointExecuterInterface[]
     */
    public function getEnabledEndpointExecuters(): array;
}
