<?php

declare(strict_types=1);

namespace PoP\ComponentModel\RelationalTypeDataLoaders;

interface TypeQueryableDataLoaderInterface
{
    public function findIDs(array $data_properties): array;
}
