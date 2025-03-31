<?php

declare(strict_types=1);

namespace PoPCMSSchema\MetaMutations\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\MetaMutations\ObjectModels\EntityMetaAlreadyHasSingleEntryErrorPayload;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractDictionaryObjectTypeDataLoader;

class EntityMetaAlreadyHasSingleEntryErrorPayloadObjectTypeDataLoader extends AbstractDictionaryObjectTypeDataLoader
{
    public function getObjectClass(): string
    {
        return EntityMetaAlreadyHasSingleEntryErrorPayload::class;
    }
}
