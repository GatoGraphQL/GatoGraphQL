<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMutations\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\PageMutations\ObjectModels\LoggedInUserHasNoPermissionToEditPageErrorPayload;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractDictionaryObjectTypeDataLoader;

class LoggedInUserHasNoPermissionToEditPageErrorPayloadObjectTypeDataLoader extends AbstractDictionaryObjectTypeDataLoader
{
    protected function getObjectClass(): string
    {
        return LoggedInUserHasNoPermissionToEditPageErrorPayload::class;
    }
}
