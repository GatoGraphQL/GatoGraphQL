<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\MenuMutations\ObjectModels\LoggedInUserHasNoPermissionToEditMenuErrorPayload;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractDictionaryObjectTypeDataLoader;

class LoggedInUserHasNoPermissionToEditMenuErrorPayloadObjectTypeDataLoader extends AbstractDictionaryObjectTypeDataLoader
{
    public function getObjectClass(): string
    {
        return LoggedInUserHasNoPermissionToEditMenuErrorPayload::class;
    }
}
