<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMutations\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\PageMutations\ObjectModels\LoggedInUserHasNoEditingPageCapabilityErrorPayload;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractDictionaryObjectTypeDataLoader;

class LoggedInUserHasNoEditingPageCapabilityErrorPayloadObjectTypeDataLoader extends AbstractDictionaryObjectTypeDataLoader
{
    protected function getObjectClass(): string
    {
        return LoggedInUserHasNoEditingPageCapabilityErrorPayload::class;
    }
}
