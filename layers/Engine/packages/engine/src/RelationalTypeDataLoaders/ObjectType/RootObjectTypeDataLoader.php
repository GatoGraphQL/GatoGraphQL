<?php

declare(strict_types=1);

namespace PoP\Engine\RelationalTypeDataLoaders\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeDataLoader;
use PoP\Engine\ObjectModels\Root;

class RootObjectTypeDataLoader extends AbstractObjectTypeDataLoader
{
    private ?Root $root = null;

    final public function setRoot(Root $root): void
    {
        $this->root = $root;
    }
    final protected function getRoot(): Root
    {
        if ($this->root === null) {
            /** @var Root */
            $root = $this->instanceManager->getInstance(Root::class);
            $this->root = $root;
        }
        return $this->root;
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
