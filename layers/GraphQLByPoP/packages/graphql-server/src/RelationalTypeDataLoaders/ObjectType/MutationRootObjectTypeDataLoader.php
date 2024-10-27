<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType;

use GraphQLByPoP\GraphQLServer\ObjectModels\MutationRoot;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeDataLoader;

class MutationRootObjectTypeDataLoader extends AbstractObjectTypeDataLoader
{
    private ?MutationRoot $mutationRoot = null;

    final protected function getMutationRoot(): MutationRoot
    {
        if ($this->mutationRoot === null) {
            /** @var MutationRoot */
            $mutationRoot = $this->instanceManager->getInstance(MutationRoot::class);
            $this->mutationRoot = $mutationRoot;
        }
        return $this->mutationRoot;
    }

    /**
     * @param array<string|int> $ids
     * @return array<object|null>
     */
    public function getObjects(array $ids): array
    {
        return [
            $this->getMutationRoot(),
        ];
    }
}
