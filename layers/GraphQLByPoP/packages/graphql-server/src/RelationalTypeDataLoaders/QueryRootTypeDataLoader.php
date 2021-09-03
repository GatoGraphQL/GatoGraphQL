<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders;

use GraphQLByPoP\GraphQLServer\ObjectFacades\QueryRootObjectFacade;
use PoP\ComponentModel\RelationalTypeDataLoaders\AbstractTypeDataLoader;

class QueryRootTypeDataLoader extends AbstractTypeDataLoader
{
    public function getObjects(array $ids): array
    {
        return [QueryRootObjectFacade::getInstance()];
    }
}
