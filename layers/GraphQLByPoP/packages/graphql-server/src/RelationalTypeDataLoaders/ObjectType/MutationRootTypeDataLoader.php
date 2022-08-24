<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType;

use GraphQLByPoP\GraphQLServer\ObjectModels\MutationRoot;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeDataLoader;

class MutationRootTypeDataLoader extends AbstractObjectTypeDataLoader
{
    private ?MutationRoot $mutationRoot = null;

    final public function setMutationRoot(MutationRoot $mutationRoot): void
    {
        $this->mutationRoot = $mutationRoot;
    }
    final protected function getMutationRoot(): MutationRoot
    {
        /** @var MutationRoot */
        return $this->mutationRoot ??= $this->instanceManager->getInstance(MutationRoot::class);
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
