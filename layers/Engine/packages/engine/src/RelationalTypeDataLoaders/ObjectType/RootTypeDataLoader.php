<?php

declare(strict_types=1);

namespace PoP\Engine\RelationalTypeDataLoaders\ObjectType;

use PoP\Engine\ObjectFacades\RootObjectFacade;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeDataLoader;

class RootTypeDataLoader extends AbstractObjectTypeDataLoader
{
    public function getObjects(array $ids): array
    {
        return [RootObjectFacade::getInstance()];
    }
}
