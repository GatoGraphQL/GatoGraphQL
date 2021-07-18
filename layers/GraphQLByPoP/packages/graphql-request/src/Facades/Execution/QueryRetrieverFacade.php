<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLRequest\Facades\Execution;

use GraphQLByPoP\GraphQLRequest\Execution\QueryRetrieverInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class QueryRetrieverFacade
{
    public static function getInstance(): QueryRetrieverInterface
    {
        /**
         * @var QueryRetrieverInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(QueryRetrieverInterface::class);
        return $service;
    }
}
