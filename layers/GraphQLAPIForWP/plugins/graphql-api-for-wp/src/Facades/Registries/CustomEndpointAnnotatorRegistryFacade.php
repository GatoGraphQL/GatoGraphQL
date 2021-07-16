<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Facades\Registries;

use PoP\Root\Container\ContainerBuilderFactory;
use GraphQLAPI\GraphQLAPI\Registries\CustomEndpointAnnotatorRegistryInterface;

class CustomEndpointAnnotatorRegistryFacade
{
    public static function getInstance(): CustomEndpointAnnotatorRegistryInterface
    {
        /**
         * @var CustomEndpointAnnotatorRegistryInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(CustomEndpointAnnotatorRegistryInterface::class);
        return $service;
    }
}
