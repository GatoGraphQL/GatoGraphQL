<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Facades\Registries;

use PoP\Root\Container\ContainerBuilderFactory;
use GraphQLAPI\GraphQLAPI\Registries\PersistedQueryEndpointExecuterRegistryInterface;

class PersistedQueryEndpointExecuterRegistryFacade
{
    public static function getInstance(): PersistedQueryEndpointExecuterRegistryInterface
    {
        /**
         * @var PersistedQueryEndpointExecuterRegistryInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(PersistedQueryEndpointExecuterRegistryInterface::class);
        return $service;
    }
}
