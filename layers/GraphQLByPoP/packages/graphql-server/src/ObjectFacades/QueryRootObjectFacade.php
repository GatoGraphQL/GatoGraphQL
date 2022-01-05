<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectFacades;

use PoP\Engine\App;
use GraphQLByPoP\GraphQLServer\ObjectModels\QueryRoot;
use PoP\Root\Container\ContainerBuilderFactory;

class QueryRootObjectFacade
{
    public static function getInstance(): QueryRoot
    {
        $containerBuilderFactory = App::getContainerBuilderFactory()->getInstance();
        return $containerBuilderFactory->get(QueryRoot::class);
    }
}
