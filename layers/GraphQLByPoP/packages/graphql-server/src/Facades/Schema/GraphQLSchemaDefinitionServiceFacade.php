<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Facades\Schema;

use PoP\Engine\App;
use GraphQLByPoP\GraphQLServer\Schema\GraphQLSchemaDefinitionServiceInterface;

class GraphQLSchemaDefinitionServiceFacade
{
    public static function getInstance(): GraphQLSchemaDefinitionServiceInterface
    {
        /**
         * @var GraphQLSchemaDefinitionServiceInterface
         */
        $service = App::getContainer()->get(GraphQLSchemaDefinitionServiceInterface::class);
        return $service;
    }
}
