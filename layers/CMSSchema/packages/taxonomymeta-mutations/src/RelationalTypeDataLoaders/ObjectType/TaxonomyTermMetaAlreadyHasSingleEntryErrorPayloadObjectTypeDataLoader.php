<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMetaMutations\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\TaxonomyMetaMutations\ObjectModels\TaxonomyTermMetaAlreadyHasSingleEntryErrorPayload;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractDictionaryObjectTypeDataLoader;

class TaxonomyTermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeDataLoader extends AbstractDictionaryObjectTypeDataLoader
{
    public function getObjectClass(): string
    {
        return TaxonomyTermMetaAlreadyHasSingleEntryErrorPayload::class;
    }
}
