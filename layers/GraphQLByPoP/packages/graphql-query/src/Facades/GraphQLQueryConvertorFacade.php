<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLQuery\Facades;

use PoP\Engine\App;
use GraphQLByPoP\GraphQLQuery\Schema\GraphQLQueryConvertorInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class GraphQLQueryConvertorFacade
{
    public static function getInstance(): GraphQLQueryConvertorInterface
    {
        /**
         * @var GraphQLQueryConvertorInterface
         */
        $service = App::getContainerBuilderFactory()->getInstance()->get(GraphQLQueryConvertorInterface::class);
        return $service;
    }
}
