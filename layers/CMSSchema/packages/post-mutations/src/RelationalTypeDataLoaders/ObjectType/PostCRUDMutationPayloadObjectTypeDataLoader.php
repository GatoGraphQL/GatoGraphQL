<?php

declare(strict_types=1);

namespace PoPSchema\PostMutations\RelationalTypeDataLoaders\ObjectType;

use PoPSchema\PostMutations\ObjectModels\PostCRUDMutationPayload;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractDictionaryObjectTypeDataLoader;

class PostCRUDMutationPayloadObjectTypeDataLoader extends AbstractDictionaryObjectTypeDataLoader
{
    protected function getObjectClass(): string
    {
        return PostCRUDMutationPayload::class;
    }
}
