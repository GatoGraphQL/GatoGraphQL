<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType;

use GraphQLByPoP\GraphQLServer\ObjectModels\QueryRoot;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeDataLoader;
use Symfony\Contracts\Service\Attribute\Required;

class QueryRootTypeDataLoader extends AbstractObjectTypeDataLoader
{
    private ?QueryRoot $queryRoot = null;

    public function setQueryRoot(QueryRoot $queryRoot): void
    {
        $this->queryRoot = $queryRoot;
    }
    protected function getQueryRoot(): QueryRoot
    {
        return $this->queryRoot ??= $this->instanceManager->getInstance(QueryRoot::class);
    }

    //#[Required]
    final public function autowireQueryRootTypeDataLoader(
        QueryRoot $queryRoot,
    ): void {
        $this->queryRoot = $queryRoot;
    }

    public function getObjects(array $ids): array
    {
        return [
            $this->getQueryRoot(),
        ];
    }
}
