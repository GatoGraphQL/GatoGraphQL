<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Facades\Registries;

use PoP\Root\App;
use GraphQLAPI\GraphQLAPI\Registries\EndpointBlockRegistryInterface;

class EndpointBlockRegistryFacade
{
    public static function getInstance(): EndpointBlockRegistryInterface
    {
        /**
         * @var EndpointBlockRegistryInterface
         */
        $service = App::getContainer()->get(EndpointBlockRegistryInterface::class);
        return $service;
    }
}
