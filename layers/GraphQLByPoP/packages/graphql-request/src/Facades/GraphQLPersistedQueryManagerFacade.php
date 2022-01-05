<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLRequest\Facades;

use PoP\Engine\App;
use GraphQLByPoP\GraphQLRequest\PersistedQueries\GraphQLPersistedQueryManagerInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class GraphQLPersistedQueryManagerFacade
{
    public static function getInstance(): GraphQLPersistedQueryManagerInterface
    {
        /**
         * @var GraphQLPersistedQueryManagerInterface
         */
        $service = App::getContainerBuilderFactory()->getInstance()->get(GraphQLPersistedQueryManagerInterface::class);
        return $service;
    }
}
