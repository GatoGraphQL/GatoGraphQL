<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Facades\Schema;

use GraphQLByPoP\GraphQLServer\Schema\FieldGraphQLSchemaDefinitionHelperInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class FieldGraphQLSchemaDefinitionHelperFacade
{
    public static function getInstance(): FieldGraphQLSchemaDefinitionHelperInterface
    {
        /**
         * @var FieldGraphQLSchemaDefinitionHelperInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(FieldGraphQLSchemaDefinitionHelperInterface::class);
        return $service;
    }
}
