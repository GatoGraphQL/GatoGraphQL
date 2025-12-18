<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\MenuMutations\ObjectModels\UserHasNoPermissionToUploadFilesForOtherUsersErrorPayload;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractDictionaryObjectTypeDataLoader;

class UserHasNoPermissionToUploadFilesForOtherUsersErrorPayloadObjectTypeDataLoader extends AbstractDictionaryObjectTypeDataLoader
{
    public function getObjectClass(): string
    {
        return UserHasNoPermissionToUploadFilesForOtherUsersErrorPayload::class;
    }
}
