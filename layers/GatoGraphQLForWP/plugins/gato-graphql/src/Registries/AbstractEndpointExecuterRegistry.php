<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Registries;

use GatoGraphQL\GatoGraphQL\Services\EndpointExecuters\EndpointExecuterInterface;
use PoP\Root\Services\ServiceInterface;

abstract class AbstractEndpointExecuterRegistry implements EndpointExecuterRegistryInterface
{
    /**
     * @var EndpointExecuterInterface[]
     */
    protected array $endpointExecuters = [];

    public function addEndpointExecuter(EndpointExecuterInterface $endpointExecuter): void
    {
        $this->endpointExecuters[] = $endpointExecuter;
    }

    /**
     * @return EndpointExecuterInterface[]
     */
    public function getEndpointExecuters(): array
    {
        return $this->endpointExecuters;
    }

    /**
     * @return EndpointExecuterInterface[]
     */
    public function getEnabledEndpointExecuters(): array
    {
        return array_values(array_filter(
            $this->getEndpointExecuters(),
            fn (ServiceInterface $service) => $service->isServiceEnabled()
        ));
    }
}
