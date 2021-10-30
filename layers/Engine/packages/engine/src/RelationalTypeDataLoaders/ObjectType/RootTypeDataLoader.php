<?php

declare(strict_types=1);

namespace PoP\Engine\RelationalTypeDataLoaders\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeDataLoader;
use PoP\Engine\ObjectModels\Root;
use Symfony\Contracts\Service\Attribute\Required;

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

    public function getObjects(array $ids): array
    {
        return [
            $this->getRoot(),
        ];
    }
}
