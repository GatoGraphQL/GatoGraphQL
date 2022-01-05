<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLQuery\Facades;

use PoP\Root\App;
use GraphQLByPoP\GraphQLQuery\Schema\GraphQLQueryConvertorInterface;

class GraphQLQueryConvertorFacade
{
    public static function getInstance(): GraphQLQueryConvertorInterface
    {
        /**
         * @var GraphQLQueryConvertorInterface
         */
        $service = App::getContainer()->get(GraphQLQueryConvertorInterface::class);
        return $service;
    }
}
