<?php

declare(strict_types=1);

namespace PoP\ComponentModel\RelationalTypeDataLoaders;

interface ObjectTypeQueryableDataLoaderInterface
{
    public function findIDs(array $data_properties): array;
}
