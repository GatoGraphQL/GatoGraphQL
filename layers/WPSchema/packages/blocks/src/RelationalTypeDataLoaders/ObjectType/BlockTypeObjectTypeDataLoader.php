<?php

declare(strict_types=1);

namespace PoPWPSchema\Blocks\RelationalTypeDataLoaders\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractDictionaryObjectTypeDataLoader;
use PoPWPSchema\Blocks\ObjectModels\BlockType;

class BlockTypeObjectTypeDataLoader extends AbstractDictionaryObjectTypeDataLoader
{
    public function getObjectClass(): string
    {
        return BlockType::class;
    }
}
