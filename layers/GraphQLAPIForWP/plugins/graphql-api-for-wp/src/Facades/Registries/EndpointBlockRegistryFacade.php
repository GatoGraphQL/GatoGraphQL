<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Facades\Registries;

use GraphQLAPI\GraphQLAPI\Registries\EndpointBlockRegistryInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class EndpointBlockRegistryFacade
{
    public static function getInstance(): EndpointBlockRegistryInterface
    {
        /**
         * @var EndpointBlockRegistryInterface
         */
        $service = \PoP\Engine\App::getContainerBuilderFactory()->getInstance()->get(EndpointBlockRegistryInterface::class);
        return $service;
    }
}
