<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMutations\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\TagMutations\ObjectModels\TagTermDoesNotExistErrorPayload;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractDictionaryObjectTypeDataLoader;

class TagTermDoesNotExistErrorPayloadObjectTypeDataLoader extends AbstractDictionaryObjectTypeDataLoader
{
    public function getObjectClass(): string
    {
        return TagTermDoesNotExistErrorPayload::class;
    }
}
