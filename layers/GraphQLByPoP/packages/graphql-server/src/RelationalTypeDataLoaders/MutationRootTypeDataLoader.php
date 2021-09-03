<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders;

use GraphQLByPoP\GraphQLServer\ObjectFacades\MutationRootObjectFacade;
use PoP\ComponentModel\RelationalTypeDataLoaders\AbstractTypeDataLoader;

class MutationRootTypeDataLoader extends AbstractTypeDataLoader
{
    public function getObjects(array $ids): array
    {
        return [MutationRootObjectFacade::getInstance()];
    }
}
