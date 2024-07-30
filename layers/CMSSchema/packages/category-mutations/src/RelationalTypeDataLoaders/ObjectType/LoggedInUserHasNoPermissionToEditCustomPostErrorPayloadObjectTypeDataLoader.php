<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\CategoryMutations\ObjectModels\LoggedInUserHasNoPermissionToEditCustomPostErrorPayload;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractDictionaryObjectTypeDataLoader;

class LoggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeDataLoader extends AbstractDictionaryObjectTypeDataLoader
{
    public function getObjectClass(): string
    {
        return LoggedInUserHasNoPermissionToEditCustomPostErrorPayload::class;
    }
}
