<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLRequest\Facades\Execution;

use PoP\Engine\App;
use GraphQLByPoP\GraphQLRequest\Execution\QueryRetrieverInterface;

class QueryRetrieverFacade
{
    public static function getInstance(): QueryRetrieverInterface
    {
        /**
         * @var QueryRetrieverInterface
         */
        $service = App::getContainer()->get(QueryRetrieverInterface::class);
        return $service;
    }
}
