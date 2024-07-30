<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\CategoryMutations\ObjectModels\LoggedInUserHasNoPublishingCustomPostCapabilityErrorPayload;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractDictionaryObjectTypeDataLoader;

class LoggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeDataLoader extends AbstractDictionaryObjectTypeDataLoader
{
    public function getObjectClass(): string
    {
        return LoggedInUserHasNoPublishingCustomPostCapabilityErrorPayload::class;
    }
}
