<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Facades\Registries;

use PoP\Root\App;
use GraphQLAPI\GraphQLAPI\Registries\PersistedQueryEndpointAnnotatorRegistryInterface;

class PersistedQueryEndpointAnnotatorRegistryFacade
{
    public static function getInstance(): PersistedQueryEndpointAnnotatorRegistryInterface
    {
        /**
         * @var PersistedQueryEndpointAnnotatorRegistryInterface
         */
        $service = App::getContainer()->get(PersistedQueryEndpointAnnotatorRegistryInterface::class);
        return $service;
    }
}
