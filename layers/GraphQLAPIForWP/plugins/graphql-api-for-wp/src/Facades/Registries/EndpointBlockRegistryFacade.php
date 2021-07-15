<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Facades\Registries;

use PoP\Root\Container\ContainerBuilderFactory;
use GraphQLAPI\GraphQLAPI\Registries\EndpointBlockRegistryInterface;

class EndpointBlockRegistryFacade
{
    public static function getInstance(): EndpointBlockRegistryInterface
    {
        /**
         * @var EndpointBlockRegistryInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(EndpointBlockRegistryInterface::class);
        return $service;
    }
}
