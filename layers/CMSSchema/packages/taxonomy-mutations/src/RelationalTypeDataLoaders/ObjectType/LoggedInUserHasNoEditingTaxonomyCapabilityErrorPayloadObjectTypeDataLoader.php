<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutations\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\TaxonomyMutations\ObjectModels\LoggedInUserHasNoEditingTaxonomyTermsCapabilityErrorPayload;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractDictionaryObjectTypeDataLoader;

class LoggedInUserHasNoEditingTaxonomyTermsCapabilityErrorPayloadObjectTypeDataLoader extends AbstractDictionaryObjectTypeDataLoader
{
    public function getObjectClass(): string
    {
        return LoggedInUserHasNoEditingTaxonomyTermsCapabilityErrorPayload::class;
    }
}
