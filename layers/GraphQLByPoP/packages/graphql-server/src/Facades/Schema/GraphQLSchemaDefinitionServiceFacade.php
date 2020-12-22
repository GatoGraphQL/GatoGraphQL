<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Facades\Schema;

use GraphQLByPoP\GraphQLServer\Schema\GraphQLSchemaDefinitionServiceInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class GraphQLSchemaDefinitionServiceFacade
{
    public static function getInstance(): GraphQLSchemaDefinitionServiceInterface
    {
        /**
         * @var GraphQLSchemaDefinitionServiceInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(GraphQLSchemaDefinitionServiceInterface::class);
        return $service;
    }
}
