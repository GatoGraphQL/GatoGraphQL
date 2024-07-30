<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutations\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\TaxonomyMutations\ObjectModels\LoggedInUserHasNoEditingTaxonomyCapabilityErrorPayload;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractDictionaryObjectTypeDataLoader;

class LoggedInUserHasNoEditingTaxonomyCapabilityErrorPayloadObjectTypeDataLoader extends AbstractDictionaryObjectTypeDataLoader
{
    public function getObjectClass(): string
    {
        return LoggedInUserHasNoEditingTaxonomyCapabilityErrorPayload::class;
    }
}
