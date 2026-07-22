<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutations\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\UserMutations\ObjectModels\LoggedInUserHasNoPermissionToDeleteUserErrorPayload;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractDictionaryObjectTypeDataLoader;

class LoggedInUserHasNoPermissionToDeleteUserErrorPayloadObjectTypeDataLoader extends AbstractDictionaryObjectTypeDataLoader
{
    public function getObjectClass(): string
    {
        return LoggedInUserHasNoPermissionToDeleteUserErrorPayload::class;
    }
}
