<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\MenuMutations\ObjectModels\UserHasNoPermissionToCreateMenusErrorPayload;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractDictionaryObjectTypeDataLoader;

class UserHasNoPermissionToCreateMenusErrorPayloadObjectTypeDataLoader extends AbstractDictionaryObjectTypeDataLoader
{
    public function getObjectClass(): string
    {
        return UserHasNoPermissionToCreateMenusErrorPayload::class;
    }
}
