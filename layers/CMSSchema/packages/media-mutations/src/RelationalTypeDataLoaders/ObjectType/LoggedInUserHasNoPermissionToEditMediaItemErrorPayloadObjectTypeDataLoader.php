<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\MediaMutations\ObjectModels\LoggedInUserHasNoPermissionToEditMediaItemErrorPayload;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractDictionaryObjectTypeDataLoader;

class LoggedInUserHasNoPermissionToEditMediaItemErrorPayloadObjectTypeDataLoader extends AbstractDictionaryObjectTypeDataLoader
{
    public function getObjectClass(): string
    {
        return LoggedInUserHasNoPermissionToEditMediaItemErrorPayload::class;
    }
}
