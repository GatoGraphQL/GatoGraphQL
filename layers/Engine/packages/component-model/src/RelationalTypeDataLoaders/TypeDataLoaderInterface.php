<?php

declare(strict_types=1);

namespace PoP\ComponentModel\RelationalTypeDataLoaders;

interface TypeDataLoaderInterface
{
    public function getObjects(array $ids): array;
}
