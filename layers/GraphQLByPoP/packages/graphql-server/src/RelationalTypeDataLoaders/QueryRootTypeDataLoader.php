<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders;

use GraphQLByPoP\GraphQLServer\ObjectFacades\QueryRootObjectFacade;
use PoP\ComponentModel\RelationalTypeDataLoaders\AbstractRelationalTypeDataLoader;

class QueryRootTypeDataLoader extends AbstractRelationalTypeDataLoader
{
    public function getObjects(array $ids): array
    {
        return [QueryRootObjectFacade::getInstance()];
    }
}
