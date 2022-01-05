<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Facades\Registries;

use GraphQLAPI\GraphQLAPI\Registries\PersistedQueryEndpointBlockRegistryInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class PersistedQueryEndpointBlockRegistryFacade
{
    public static function getInstance(): PersistedQueryEndpointBlockRegistryInterface
    {
        /**
         * @var PersistedQueryEndpointBlockRegistryInterface
         */
        $service = \PoP\Engine\App::getContainerBuilderFactory()->getInstance()->get(PersistedQueryEndpointBlockRegistryInterface::class);
        return $service;
    }
}
