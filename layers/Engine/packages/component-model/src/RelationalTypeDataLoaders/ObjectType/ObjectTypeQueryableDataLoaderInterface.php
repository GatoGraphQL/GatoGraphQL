<?php

declare(strict_types=1);

namespace PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType;

interface ObjectTypeQueryableDataLoaderInterface
{
    /**
     * @param array<string,mixed> $data_properties
     * @return array<string|int>
     */
    public function findIDs(array $data_properties): array;
}
