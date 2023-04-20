<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Facades\Registries;

use PoP\Root\App;
use GraphQLAPI\GraphQLAPI\Registries\PublicPersistedQueryEndpointAnnotatorRegistryInterface;

class PublicPersistedQueryEndpointAnnotatorRegistryFacade
{
    public static function getInstance(): PublicPersistedQueryEndpointAnnotatorRegistryInterface
    {
        /**
         * @var PublicPersistedQueryEndpointAnnotatorRegistryInterface
         */
        $service = App::getContainer()->get(PublicPersistedQueryEndpointAnnotatorRegistryInterface::class);
        return $service;
    }
}
