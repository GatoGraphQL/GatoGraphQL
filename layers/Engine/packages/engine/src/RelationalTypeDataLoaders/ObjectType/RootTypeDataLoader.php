<?php

declare(strict_types=1);

namespace PoP\Engine\RelationalTypeDataLoaders\ObjectType;

use PoP\Hooks\HooksAPIInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeDataLoader;
use PoP\Engine\ObjectModels\Root;

class RootTypeDataLoader extends AbstractObjectTypeDataLoader
{
    protected Root $root;
    public function __construct(
        Root $root,
    ) {
        $this->root = $root;
    }

    public function getObjects(array $ids): array
    {
        return [
            $this->root,
        ];
    }
}
