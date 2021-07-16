<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Registries;

use GraphQLAPI\GraphQLAPI\Services\EndpointExecuters\EndpointExecuterInterface;
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
        // Only enabled services
        return array_filter(
            $this->endpointExecuters,
            fn (ServiceInterface $service) => $service->isServiceEnabled()
        );
    }
}
