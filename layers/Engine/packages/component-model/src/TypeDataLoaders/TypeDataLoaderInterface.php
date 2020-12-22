<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeDataLoaders;

interface TypeDataLoaderInterface
{
    public function getObjects(array $ids): array;
}
