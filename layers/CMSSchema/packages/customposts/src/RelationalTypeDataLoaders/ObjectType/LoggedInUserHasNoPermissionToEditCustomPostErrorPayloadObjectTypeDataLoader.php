<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\CustomPosts\ObjectModels\LoggedInUserHasNoPermissionToEditCustomPostErrorPayload;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractDictionaryObjectTypeDataLoader;

class LoggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeDataLoader extends AbstractDictionaryObjectTypeDataLoader
{
    protected function getObjectClass(): string
    {
        return LoggedInUserHasNoPermissionToEditCustomPostErrorPayload::class;
    }
}
