<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\CategoryMutations\ObjectModels\LoggedInUserHasNoEditingCategoryCapabilityErrorPayload;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractDictionaryObjectTypeDataLoader;

class LoggedInUserHasNoEditingCategoryCapabilityErrorPayloadObjectTypeDataLoader extends AbstractDictionaryObjectTypeDataLoader
{
    public function getObjectClass(): string
    {
        return LoggedInUserHasNoEditingCategoryCapabilityErrorPayload::class;
    }
}
