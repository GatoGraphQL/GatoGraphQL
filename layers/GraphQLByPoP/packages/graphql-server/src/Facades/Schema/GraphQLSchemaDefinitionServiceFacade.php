<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Facades\Schema;

use PoP\Engine\App;
use GraphQLByPoP\GraphQLServer\Schema\GraphQLSchemaDefinitionServiceInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class GraphQLSchemaDefinitionServiceFacade
{
    public static function getInstance(): GraphQLSchemaDefinitionServiceInterface
    {
        /**
         * @var GraphQLSchemaDefinitionServiceInterface
         */
        $service = App::getContainerBuilderFactory()->getInstance()->get(GraphQLSchemaDefinitionServiceInterface::class);
        return $service;
    }
}
