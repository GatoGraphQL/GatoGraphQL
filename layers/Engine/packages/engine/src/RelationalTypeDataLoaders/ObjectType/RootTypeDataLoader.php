<?php

declare(strict_types=1);

namespace PoP\Engine\RelationalTypeDataLoaders\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeDataLoader;
use PoP\Engine\ObjectModels\Root;
use Symfony\Contracts\Service\Attribute\Required;

class RootTypeDataLoader extends AbstractObjectTypeDataLoader
{
    protected ?Root $root = null;

    public function setRoot(Root $root): void
    {
        $this->root = $root;
    }
    protected function getRoot(): Root
    {
        return $this->root ??= $this->getInstanceManager()->getInstance(Root::class);
    }

    //#[Required]
    final public function autowireRootTypeDataLoader(
        Root $root,
    ): void {
        $this->root = $root;
    }

    public function getObjects(array $ids): array
    {
        return [
            $this->getRoot(),
        ];
    }
}
