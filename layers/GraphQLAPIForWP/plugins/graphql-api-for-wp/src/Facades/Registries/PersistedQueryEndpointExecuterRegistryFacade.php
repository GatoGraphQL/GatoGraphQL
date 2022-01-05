<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Facades\Registries;

use PoP\Engine\App;
use GraphQLAPI\GraphQLAPI\Registries\PersistedQueryEndpointExecuterRegistryInterface;

class PersistedQueryEndpointExecuterRegistryFacade
{
    public static function getInstance(): PersistedQueryEndpointExecuterRegistryInterface
    {
        /**
         * @var PersistedQueryEndpointExecuterRegistryInterface
         */
        $service = App::getContainer()->get(PersistedQueryEndpointExecuterRegistryInterface::class);
        return $service;
    }
}
