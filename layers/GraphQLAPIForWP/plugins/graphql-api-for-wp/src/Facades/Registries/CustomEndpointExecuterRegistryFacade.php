<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Facades\Registries;

use PoP\Engine\App;
use GraphQLAPI\GraphQLAPI\Registries\CustomEndpointExecuterRegistryInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class CustomEndpointExecuterRegistryFacade
{
    public static function getInstance(): CustomEndpointExecuterRegistryInterface
    {
        /**
         * @var CustomEndpointExecuterRegistryInterface
         */
        $service = App::getContainerBuilderFactory()->getInstance()->get(CustomEndpointExecuterRegistryInterface::class);
        return $service;
    }
}
