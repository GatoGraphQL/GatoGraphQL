<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLQuery\Facades;

use GraphQLByPoP\GraphQLQuery\Schema\GraphQLQueryConvertorInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class GraphQLQueryConvertorFacade
{
    public static function getInstance(): GraphQLQueryConvertorInterface
    {
        /**
         * @var GraphQLQueryConvertorInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(GraphQLQueryConvertorInterface::class);
        return $service;
    }
}
