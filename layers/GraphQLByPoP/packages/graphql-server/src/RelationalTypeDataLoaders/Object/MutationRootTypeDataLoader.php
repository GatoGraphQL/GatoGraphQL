<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\Object;

use GraphQLByPoP\GraphQLServer\ObjectFacades\MutationRootObjectFacade;
use PoP\ComponentModel\RelationalTypeDataLoaders\Object\AbstractObjectTypeDataLoader;

class MutationRootTypeDataLoader extends AbstractObjectTypeDataLoader
{
    public function getObjects(array $ids): array
    {
        return [MutationRootObjectFacade::getInstance()];
    }
}
