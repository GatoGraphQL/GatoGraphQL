<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Facades\Query;

use PoP\Root\App;
use PoP\GraphQLParser\Query\QueryAugmenterServiceInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class QueryAugmenterServiceFacade
{
    public static function getInstance(): QueryAugmenterServiceInterface
    {
        /**
         * @var QueryAugmenterServiceInterface
         */
        $service = App::getContainerBuilderFactory()->getInstance()->get(QueryAugmenterServiceInterface::class);
        return $service;
    }
}
