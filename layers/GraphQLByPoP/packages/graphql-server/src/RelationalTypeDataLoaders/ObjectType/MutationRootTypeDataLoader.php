<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType;

use GraphQLByPoP\GraphQLServer\ObjectModels\MutationRoot;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeDataLoader;
use Symfony\Contracts\Service\Attribute\Required;

class MutationRootTypeDataLoader extends AbstractObjectTypeDataLoader
{
    protected MutationRoot $mutationRoot;

    #[Required]
    public function autowireMutationRootTypeDataLoader(
        MutationRoot $mutationRoot,
    ): void {
        $this->mutationRoot = $mutationRoot;
    }

    public function getObjects(array $ids): array
    {
        return [
            $this->mutationRoot,
        ];
    }
}
