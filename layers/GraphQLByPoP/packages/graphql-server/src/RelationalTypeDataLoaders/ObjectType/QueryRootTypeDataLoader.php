<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType;

use GraphQLByPoP\GraphQLServer\ObjectModels\QueryRoot;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeDataLoader;

class QueryRootTypeDataLoader extends AbstractObjectTypeDataLoader
{
    private ?QueryRoot $queryRoot = null;

    final public function setQueryRoot(QueryRoot $queryRoot): void
    {
        $this->queryRoot = $queryRoot;
    }
    final protected function getQueryRoot(): QueryRoot
    {
        /** @var QueryRoot */
        return $this->queryRoot ??= $this->instanceManager->getInstance(QueryRoot::class);
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
