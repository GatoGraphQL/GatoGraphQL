<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Facades\Registries;

use GraphQLAPI\GraphQLAPI\Registries\CustomEndpointAnnotatorRegistryInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class CustomEndpointAnnotatorRegistryFacade
{
    public static function getInstance(): CustomEndpointAnnotatorRegistryInterface
    {
        /**
         * @var CustomEndpointAnnotatorRegistryInterface
         */
        $service = \PoP\Engine\App::getContainerBuilderFactory()->getInstance()->get(CustomEndpointAnnotatorRegistryInterface::class);
        return $service;
    }
}
