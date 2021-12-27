<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Facades\Query;

use PoP\GraphQLParser\Query\QueryAugmenterServiceInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class QueryAugmenterServiceFacade
{
    public static function getInstance(): QueryAugmenterServiceInterface
    {
        /**
         * @var QueryAugmenterServiceInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(QueryAugmenterServiceInterface::class);
        return $service;
    }
}
