<?php

declare(strict_types=1);

namespace PoP\ComponentModel\RelationalTypeDataLoaders;

interface RelationalTypeDataLoaderInterface
{
    public function getObjects(array $ids): array;
}
