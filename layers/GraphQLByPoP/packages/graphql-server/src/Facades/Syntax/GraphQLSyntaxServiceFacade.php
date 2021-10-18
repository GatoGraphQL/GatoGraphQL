<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Facades\Syntax;

use GraphQLByPoP\GraphQLServer\Syntax\GraphQLSyntaxServiceInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class GraphQLSyntaxServiceFacade
{
    public static function getInstance(): GraphQLSyntaxServiceInterface
    {
        /**
         * @var GraphQLSyntaxServiceInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(GraphQLSyntaxServiceInterface::class);
        return $service;
    }
}
