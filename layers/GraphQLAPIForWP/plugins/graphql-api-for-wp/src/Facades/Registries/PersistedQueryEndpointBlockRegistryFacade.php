<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Facades\Registries;

use PoP\Engine\App;
use GraphQLAPI\GraphQLAPI\Registries\PersistedQueryEndpointBlockRegistryInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class PersistedQueryEndpointBlockRegistryFacade
{
    public static function getInstance(): PersistedQueryEndpointBlockRegistryInterface
    {
        /**
         * @var PersistedQueryEndpointBlockRegistryInterface
         */
        $service = App::getContainerBuilderFactory()->getInstance()->get(PersistedQueryEndpointBlockRegistryInterface::class);
        return $service;
    }
}
