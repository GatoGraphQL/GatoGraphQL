<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Facades\AppStateProviderServices;

use PoP\Root\App;
use GraphQLByPoP\GraphQLServer\AppStateProviderServices\GraphQLServerAppStateProviderServiceInterface;

class GraphQLServerAppStateProviderServiceFacade
{
    public static function getInstance(): GraphQLServerAppStateProviderServiceInterface
    {
        /**
         * @var GraphQLServerAppStateProviderServiceInterface
         */
        $service = App::getContainer()->get(GraphQLServerAppStateProviderServiceInterface::class);
        return $service;
    }
}
