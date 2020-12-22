<?php

declare(strict_types=1);

namespace PoP\Engine\TypeDataLoaders;

use PoP\Engine\ObjectFacades\RootObjectFacade;
use PoP\ComponentModel\TypeDataLoaders\AbstractTypeDataLoader;

class RootTypeDataLoader extends AbstractTypeDataLoader
{
    public function getObjects(array $ids): array
    {
        return [RootObjectFacade::getInstance()];
    }
}
