<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Registries;

use GraphQLAPI\GraphQLAPI\Services\EndpointExecuters\EndpointExecuterInterface;

interface EndpointExecuterRegistryInterface
{
    public function addEndpointExecuter(EndpointExecuterInterface $endpointExecuter): void;
    /**
     * @return EndpointExecuterInterface[]
     */
    public function getEndpointExecuters(): array;
}
