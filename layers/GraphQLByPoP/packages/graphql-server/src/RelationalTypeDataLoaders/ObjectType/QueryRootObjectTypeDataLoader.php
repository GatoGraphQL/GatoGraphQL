<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType;

use GraphQLByPoP\GraphQLServer\ObjectModels\QueryRoot;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeDataLoader;

class QueryRootObjectTypeDataLoader extends AbstractObjectTypeDataLoader
{
    private ?QueryRoot $queryRoot = null;

    final public function setQueryRoot(QueryRoot $queryRoot): void
    {
        $this->queryRoot = $queryRoot;
    }
    final protected function getQueryRoot(): QueryRoot
    {
        if ($this->queryRoot === null) {
            /** @var QueryRoot */
            $queryRoot = $this->instanceManager->getInstance(QueryRoot::class);
            $this->queryRoot = $queryRoot;
        }
        return $this->queryRoot;
    }

    /**
     * @param array<string|int> $ids
     * @return array<object|null>
     */
    public function getObjects(array $ids): array
    {
        return [
            $this->getQueryRoot(),
        ];
    }
}
