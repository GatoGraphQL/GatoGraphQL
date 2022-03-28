<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Facades\Query;

use PoP\Root\App;
use PoP\GraphQLParser\Query\GraphQLQueryStringFormatterInterface;

class GraphQLQueryStringFormatterFacade
{
    public static function getInstance(): GraphQLQueryStringFormatterInterface
    {
        /**
         * @var GraphQLQueryStringFormatterInterface
         */
        $service = App::getContainer()->get(GraphQLQueryStringFormatterInterface::class);
        return $service;
    }
}
