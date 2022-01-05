<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Facades\Registries;

use PoP\Root\App;
use GraphQLAPI\GraphQLAPI\Registries\PersistedQueryEndpointBlockRegistryInterface;

class PersistedQueryEndpointBlockRegistryFacade
{
    public static function getInstance(): PersistedQueryEndpointBlockRegistryInterface
    {
        /**
         * @var PersistedQueryEndpointBlockRegistryInterface
         */
        $service = App::getContainer()->get(PersistedQueryEndpointBlockRegistryInterface::class);
        return $service;
    }
}
