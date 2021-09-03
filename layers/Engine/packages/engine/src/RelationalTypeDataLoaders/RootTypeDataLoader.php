<?php

declare(strict_types=1);

namespace PoP\Engine\RelationalTypeDataLoaders;

use PoP\Engine\ObjectFacades\RootObjectFacade;
use PoP\ComponentModel\RelationalTypeDataLoaders\AbstractRelationalTypeDataLoader;

class RootTypeDataLoader extends AbstractRelationalTypeDataLoader
{
    public function getObjects(array $ids): array
    {
        return [RootObjectFacade::getInstance()];
    }
}
