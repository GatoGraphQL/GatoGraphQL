<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMutations\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\PostMutations\ObjectModels\PostCRUDMutationPayload;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractDictionaryObjectTypeDataLoader;

class PostCRUDMutationPayloadObjectTypeDataLoader extends AbstractDictionaryObjectTypeDataLoader
{
    protected function getObjectClass(): string
    {
        return PostCRUDMutationPayload::class;
    }
}
