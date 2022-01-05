<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLRequest\Facades;

use PoP\Engine\App;
use GraphQLByPoP\GraphQLRequest\PersistedQueries\GraphQLPersistedQueryManagerInterface;

class GraphQLPersistedQueryManagerFacade
{
    public static function getInstance(): GraphQLPersistedQueryManagerInterface
    {
        /**
         * @var GraphQLPersistedQueryManagerInterface
         */
        $service = App::getContainer()->get(GraphQLPersistedQueryManagerInterface::class);
        return $service;
    }
}
