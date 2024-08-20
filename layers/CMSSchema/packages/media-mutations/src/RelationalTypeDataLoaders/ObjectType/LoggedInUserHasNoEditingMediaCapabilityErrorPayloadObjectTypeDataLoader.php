<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\MediaMutations\ObjectModels\LoggedInUserHasNoEditingMediaCapabilityErrorPayload;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractDictionaryObjectTypeDataLoader;

class LoggedInUserHasNoEditingMediaCapabilityErrorPayloadObjectTypeDataLoader extends AbstractDictionaryObjectTypeDataLoader
{
    public function getObjectClass(): string
    {
        return LoggedInUserHasNoEditingMediaCapabilityErrorPayload::class;
    }
}
