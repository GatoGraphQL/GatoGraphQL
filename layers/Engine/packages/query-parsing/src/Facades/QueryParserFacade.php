<?php

declare(strict_types=1);

namespace PoP\QueryParsing\Facades;

use PoP\Root\App;
use PoP\QueryParsing\QueryParserInterface;

class QueryParserFacade
{
    public static function getInstance(): QueryParserInterface
    {
        /**
         * @var QueryParserInterface
         */
        $service = App::getContainer()->get(QueryParserInterface::class);
        return $service;
    }
}
