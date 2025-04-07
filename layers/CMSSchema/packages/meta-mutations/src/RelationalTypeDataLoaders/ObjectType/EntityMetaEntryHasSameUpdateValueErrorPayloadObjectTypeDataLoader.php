<?php

declare(strict_types=1);

namespace PoPCMSSchema\MetaMutations\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\MetaMutations\ObjectModels\EntityMetaEntryHasSameUpdateValueErrorPayload;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractDictionaryObjectTypeDataLoader;

class EntityMetaEntryHasSameUpdateValueErrorPayloadObjectTypeDataLoader extends AbstractDictionaryObjectTypeDataLoader
{
    public function getObjectClass(): string
    {
        return EntityMetaEntryHasSameUpdateValueErrorPayload::class;
    }
}
