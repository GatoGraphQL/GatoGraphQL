<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\MediaMutations\ObjectModels\UserHasNoPermissionToUploadFilesForOtherUsersErrorPayload;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractDictionaryObjectTypeDataLoader;

class UserHasNoPermissionToUploadFilesForOtherUsersErrorPayloadObjectTypeDataLoader extends AbstractDictionaryObjectTypeDataLoader
{
    protected function getObjectClass(): string
    {
        return UserHasNoPermissionToUploadFilesForOtherUsersErrorPayload::class;
    }
}
