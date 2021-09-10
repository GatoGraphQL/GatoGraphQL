<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType;

use GraphQLByPoP\GraphQLServer\ObjectFacades\QueryRootObjectFacade;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeDataLoader;

class QueryRootTypeDataLoader extends AbstractObjectTypeDataLoader
{
    public function getObjects(array $ids): array
    {
        return [QueryRootObjectFacade::getInstance()];
    }
}
