<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectFacades;

use GraphQLByPoP\GraphQLServer\ObjectModels\QueryRootObject;
use PoP\Root\Container\ContainerBuilderFactory;

class QueryRootObjectFacade
{
    public static function getInstance(): QueryRootObject
    {
        $containerBuilderFactory = ContainerBuilderFactory::getInstance();
        return $containerBuilderFactory->get(QueryRootObject::class);
    }
}
