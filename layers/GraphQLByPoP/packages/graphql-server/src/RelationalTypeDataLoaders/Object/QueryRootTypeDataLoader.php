<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\Object;

use GraphQLByPoP\GraphQLServer\ObjectFacades\QueryRootObjectFacade;
use PoP\ComponentModel\RelationalTypeDataLoaders\Object\AbstractObjectTypeDataLoader;

class QueryRootTypeDataLoader extends AbstractObjectTypeDataLoader
{
    public function getObjects(array $ids): array
    {
        return [QueryRootObjectFacade::getInstance()];
    }
}
