<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutations\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\TaxonomyMutations\ObjectModels\LoggedInUserHasNoPermissionToEditCustomPostErrorPayload;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractDictionaryObjectTypeDataLoader;

class LoggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeDataLoader extends AbstractDictionaryObjectTypeDataLoader
{
    public function getObjectClass(): string
    {
        return LoggedInUserHasNoPermissionToEditCustomPostErrorPayload::class;
    }
}
