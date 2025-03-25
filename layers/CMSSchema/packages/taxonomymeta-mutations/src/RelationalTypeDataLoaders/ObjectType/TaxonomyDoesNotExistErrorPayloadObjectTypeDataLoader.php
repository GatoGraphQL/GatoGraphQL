<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMetaMutations\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\TaxonomyMetaMutations\ObjectModels\TaxonomyDoesNotExistErrorPayload;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractDictionaryObjectTypeDataLoader;

class TaxonomyDoesNotExistErrorPayloadObjectTypeDataLoader extends AbstractDictionaryObjectTypeDataLoader
{
    public function getObjectClass(): string
    {
        return TaxonomyDoesNotExistErrorPayload::class;
    }
}
