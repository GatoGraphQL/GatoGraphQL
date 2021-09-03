<?php

declare(strict_types=1);

namespace PoP\Engine\RelationalTypeDataLoaders;

use PoP\Engine\ObjectFacades\RootObjectFacade;
use PoP\ComponentModel\RelationalTypeDataLoaders\AbstractTypeDataLoader;

class RootTypeDataLoader extends AbstractTypeDataLoader
{
    public function getObjects(array $ids): array
    {
        return [RootObjectFacade::getInstance()];
    }
}
