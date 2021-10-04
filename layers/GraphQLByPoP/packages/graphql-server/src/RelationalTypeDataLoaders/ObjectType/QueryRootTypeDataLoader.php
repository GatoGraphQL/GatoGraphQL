<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType;

use GraphQLByPoP\GraphQLServer\ObjectModels\QueryRoot;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeDataLoader;
use Symfony\Contracts\Service\Attribute\Required;

class QueryRootTypeDataLoader extends AbstractObjectTypeDataLoader
{
    protected QueryRoot $queryRoot;

    #[Required]
    final public function autowireQueryRootTypeDataLoader(
        QueryRoot $queryRoot,
    ): void {
        $this->queryRoot = $queryRoot;
    }

    public function getObjects(array $ids): array
    {
        return [
            $this->queryRoot,
        ];
    }
}
