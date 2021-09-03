<?php

declare(strict_types=1);

namespace PoP\ComponentModel\RelationalTypeDataLoaders;

interface RelationalTypeQueryableDataLoaderInterface
{
    public function findIDs(array $data_properties): array;
}
