<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectFacades;

use PoP\Root\App;
use GraphQLByPoP\GraphQLServer\ObjectModels\QueryRoot;

class QueryRootObjectFacade
{
    public static function getInstance(): QueryRoot
    {
        $containerBuilderFactory = App::getContainer();
        return $containerBuilderFactory->get(QueryRoot::class);
    }
}
