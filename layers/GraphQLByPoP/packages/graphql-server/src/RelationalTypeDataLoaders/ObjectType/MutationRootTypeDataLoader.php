<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType;

use Symfony\Contracts\Service\Attribute\Required;
use GraphQLByPoP\GraphQLServer\ObjectModels\MutationRoot;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeDataLoader;

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
