<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\TypeDataLoaders;

use GraphQLByPoP\GraphQLServer\ObjectFacades\QueryRootObjectFacade;
use PoP\ComponentModel\TypeDataLoaders\AbstractTypeDataLoader;

class QueryRootTypeDataLoader extends AbstractTypeDataLoader
{
    public function getObjects(array $ids): array
    {
        return [QueryRootObjectFacade::getInstance()];
    }
}
