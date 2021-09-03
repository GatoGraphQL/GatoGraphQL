<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders;

use GraphQLByPoP\GraphQLServer\ObjectFacades\MutationRootObjectFacade;
use PoP\ComponentModel\RelationalTypeDataLoaders\AbstractRelationalTypeDataLoader;

class MutationRootTypeDataLoader extends AbstractRelationalTypeDataLoader
{
    public function getObjects(array $ids): array
    {
        return [MutationRootObjectFacade::getInstance()];
    }
}
