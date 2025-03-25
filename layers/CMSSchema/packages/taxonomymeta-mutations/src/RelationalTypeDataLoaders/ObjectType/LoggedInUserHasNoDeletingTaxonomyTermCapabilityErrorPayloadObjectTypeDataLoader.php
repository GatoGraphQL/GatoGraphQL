<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMetaMutations\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\TaxonomyMetaMutations\ObjectModels\LoggedInUserHasNoDeletingTaxonomyTermCapabilityErrorPayload;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractDictionaryObjectTypeDataLoader;

class LoggedInUserHasNoDeletingTaxonomyTermCapabilityErrorPayloadObjectTypeDataLoader extends AbstractDictionaryObjectTypeDataLoader
{
    public function getObjectClass(): string
    {
        return LoggedInUserHasNoDeletingTaxonomyTermCapabilityErrorPayload::class;
    }
}
