<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Facades\Registries;

use PoP\Engine\App;
use GraphQLAPI\GraphQLAPI\Registries\EndpointBlockRegistryInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class EndpointBlockRegistryFacade
{
    public static function getInstance(): EndpointBlockRegistryInterface
    {
        /**
         * @var EndpointBlockRegistryInterface
         */
        $service = App::getContainerBuilderFactory()->getInstance()->get(EndpointBlockRegistryInterface::class);
        return $service;
    }
}
