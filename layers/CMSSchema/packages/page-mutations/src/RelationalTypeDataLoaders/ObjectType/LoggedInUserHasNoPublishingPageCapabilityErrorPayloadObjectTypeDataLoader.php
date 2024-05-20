<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMutations\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\PageMutations\ObjectModels\LoggedInUserHasNoPublishingPageCapabilityErrorPayload;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractDictionaryObjectTypeDataLoader;

class LoggedInUserHasNoPublishingPageCapabilityErrorPayloadObjectTypeDataLoader extends AbstractDictionaryObjectTypeDataLoader
{
    protected function getObjectClass(): string
    {
        return LoggedInUserHasNoPublishingPageCapabilityErrorPayload::class;
    }
}
