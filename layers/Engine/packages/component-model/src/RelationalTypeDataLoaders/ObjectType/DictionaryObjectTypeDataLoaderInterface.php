<?php

declare(strict_types=1);

namespace PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

interface DictionaryObjectTypeDataLoaderInterface extends RelationalTypeDataLoaderInterface
{
    public function getObjectClass(): string;
}
