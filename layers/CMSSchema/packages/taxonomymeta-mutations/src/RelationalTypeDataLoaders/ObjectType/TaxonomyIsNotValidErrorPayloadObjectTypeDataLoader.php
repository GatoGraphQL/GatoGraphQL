<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMetaMutations\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\TaxonomyMetaMutations\ObjectModels\TaxonomyIsNotValidErrorPayload;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractDictionaryObjectTypeDataLoader;

class TaxonomyIsNotValidErrorPayloadObjectTypeDataLoader extends AbstractDictionaryObjectTypeDataLoader
{
    public function getObjectClass(): string
    {
        return TaxonomyIsNotValidErrorPayload::class;
    }
}
