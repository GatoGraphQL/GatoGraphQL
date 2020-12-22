<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeDataLoaders;

interface TypeQueryableDataLoaderInterface
{
    public function findIDs(array $data_properties): array;
    public function getFilterDataloadingModule(): ?array;
}
