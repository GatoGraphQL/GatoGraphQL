<?php

declare(strict_types=1);

namespace PoP\ComponentModel\RelationalTypeDataLoaders;

interface RelationalTypeDataLoaderInterface
{
    /**
     * @param array<string | int> $ids
     * @return array<object|null>
     */
    public function getObjects(array $ids): array;
}
