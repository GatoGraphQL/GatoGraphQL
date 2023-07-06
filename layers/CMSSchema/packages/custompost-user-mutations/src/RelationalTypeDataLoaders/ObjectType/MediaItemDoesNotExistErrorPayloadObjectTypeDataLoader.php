<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostUserMutations\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\CustomPostUserMutations\ObjectModels\MediaItemDoesNotExistErrorPayload;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractDictionaryObjectTypeDataLoader;

class MediaItemDoesNotExistErrorPayloadObjectTypeDataLoader extends AbstractDictionaryObjectTypeDataLoader
{
    protected function getObjectClass(): string
    {
        return MediaItemDoesNotExistErrorPayload::class;
    }
}
