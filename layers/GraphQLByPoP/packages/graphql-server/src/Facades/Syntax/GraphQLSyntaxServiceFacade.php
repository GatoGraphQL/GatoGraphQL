<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Facades\Syntax;

use PoP\Root\App;
use GraphQLByPoP\GraphQLServer\Syntax\GraphQLSyntaxServiceInterface;

class GraphQLSyntaxServiceFacade
{
    public static function getInstance(): GraphQLSyntaxServiceInterface
    {
        /**
         * @var GraphQLSyntaxServiceInterface
         */
        $service = App::getContainer()->get(GraphQLSyntaxServiceInterface::class);
        return $service;
    }
}
