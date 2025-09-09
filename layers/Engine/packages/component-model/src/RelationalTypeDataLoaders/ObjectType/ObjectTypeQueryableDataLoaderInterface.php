<?php

declare(strict_types=1);

namespace PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

interface ObjectTypeQueryableDataLoaderInterface extends RelationalTypeDataLoaderInterface
{
    /**
     * @param array<string,mixed> $data_properties
     * @return array<string|int>
     */
    public function findIDs(array $data_properties): array;
}
