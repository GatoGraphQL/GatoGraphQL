<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Facades\Registries;

use GraphQLAPI\GraphQLAPI\Registries\PersistedQueryEndpointExecuterRegistryInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class PersistedQueryEndpointExecuterRegistryFacade
{
    public static function getInstance(): PersistedQueryEndpointExecuterRegistryInterface
    {
        /**
         * @var PersistedQueryEndpointExecuterRegistryInterface
         */
        $service = \PoP\Engine\App::getContainerBuilderFactory()->getInstance()->get(PersistedQueryEndpointExecuterRegistryInterface::class);
        return $service;
    }
}
