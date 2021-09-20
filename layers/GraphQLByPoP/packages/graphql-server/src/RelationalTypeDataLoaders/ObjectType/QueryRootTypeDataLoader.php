<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType;

use PoP\Hooks\HooksAPIInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\LooseContracts\NameResolverInterface;
use GraphQLByPoP\GraphQLServer\ObjectModels\QueryRoot;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeDataLoader;

class QueryRootTypeDataLoader extends AbstractObjectTypeDataLoader
{
    public function __construct(
        HooksAPIInterface $hooksAPI,
        InstanceManagerInterface $instanceManager,
        NameResolverInterface $nameResolver,
        protected QueryRoot $queryRoot,
    ) {
        parent::__construct(
            $hooksAPI,
            $instanceManager,
            $nameResolver,
        );
    }

    public function getObjects(array $ids): array
    {
        return [
            $this->queryRoot,
        ];
    }
}
