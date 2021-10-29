<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType;

use GraphQLByPoP\GraphQLServer\ObjectModels\MutationRoot;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeDataLoader;
use Symfony\Contracts\Service\Attribute\Required;

class MutationRootTypeDataLoader extends AbstractObjectTypeDataLoader
{
    private ?MutationRoot $mutationRoot = null;

    public function setMutationRoot(MutationRoot $mutationRoot): void
    {
        $this->mutationRoot = $mutationRoot;
    }
    protected function getMutationRoot(): MutationRoot
    {
        return $this->mutationRoot ??= $this->instanceManager->getInstance(MutationRoot::class);
    }

    public function getObjects(array $ids): array
    {
        return [
            $this->getMutationRoot(),
        ];
    }
}
