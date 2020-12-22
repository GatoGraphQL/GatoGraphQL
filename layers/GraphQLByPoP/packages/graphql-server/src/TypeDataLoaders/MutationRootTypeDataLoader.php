<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\TypeDataLoaders;

use GraphQLByPoP\GraphQLServer\ObjectFacades\MutationRootObjectFacade;
use PoP\ComponentModel\TypeDataLoaders\AbstractTypeDataLoader;

class MutationRootTypeDataLoader extends AbstractTypeDataLoader
{
    public function getObjects(array $ids): array
    {
        return [MutationRootObjectFacade::getInstance()];
    }
}
