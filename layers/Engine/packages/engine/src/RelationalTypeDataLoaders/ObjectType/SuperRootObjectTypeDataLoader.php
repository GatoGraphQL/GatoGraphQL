<?php

declare(strict_types=1);

namespace PoP\Engine\RelationalTypeDataLoaders\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeDataLoader;
use PoP\Engine\ObjectModels\SuperRoot;

class SuperRootObjectTypeDataLoader extends AbstractObjectTypeDataLoader
{
    private ?SuperRoot $superRoot = null;

    final public function setSuperRoot(SuperRoot $superRoot): void
    {
        $this->superRoot = $superRoot;
    }
    final protected function getSuperRoot(): SuperRoot
    {
        /** @var SuperRoot */
        return $this->superRoot ??= $this->instanceManager->getInstance(SuperRoot::class);
    }

    /**
     * @param array<string|int> $ids
     * @return array<object|null>
     */
    public function getObjects(array $ids): array
    {
        return [
            $this->getSuperRoot(),
        ];
    }
}
