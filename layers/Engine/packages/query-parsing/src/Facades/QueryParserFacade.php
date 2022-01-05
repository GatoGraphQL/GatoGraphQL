<?php

declare(strict_types=1);

namespace PoP\QueryParsing\Facades;

use PoP\QueryParsing\QueryParserInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class QueryParserFacade
{
    public static function getInstance(): QueryParserInterface
    {
        /**
         * @var QueryParserInterface
         */
        $service = \PoP\Root\App::getContainerBuilderFactory()->getInstance()->get(QueryParserInterface::class);
        return $service;
    }
}
