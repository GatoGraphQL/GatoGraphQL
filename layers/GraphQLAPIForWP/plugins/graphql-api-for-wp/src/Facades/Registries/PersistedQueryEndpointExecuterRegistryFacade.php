<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Facades\Registries;

use PoP\Engine\App;
use GraphQLAPI\GraphQLAPI\Registries\PersistedQueryEndpointExecuterRegistryInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class PersistedQueryEndpointExecuterRegistryFacade
{
    public static function getInstance(): PersistedQueryEndpointExecuterRegistryInterface
    {
        /**
         * @var PersistedQueryEndpointExecuterRegistryInterface
         */
        $service = App::getContainerBuilderFactory()->getInstance()->get(PersistedQueryEndpointExecuterRegistryInterface::class);
        return $service;
    }
}
