<?php

declare(strict_types=1);

namespace PoP\Engine\RelationalTypeDataLoaders\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeDataLoader;
use PoP\Engine\ObjectModels\Root;

class RootTypeDataLoader extends AbstractObjectTypeDataLoader
{
    private ?Root $root = null;

    final public function setRoot(Root $root): void
    {
        $this->root = $root;
    }
    final protected function getRoot(): Root
    {
        return $this->root ??= $this->instanceManager->getInstance(Root::class);
    }

    /**
     * @param array<string|int> $ids
     * @return array<object|null>
     */
    public function getObjects(array $ids): array
    {
        return [
            $this->getRoot(),
        ];
    }
}
