<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutations\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\TaxonomyMutations\ObjectModels\TaxonomyDoesNotExistErrorPayload;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractDictionaryObjectTypeDataLoader;

class TaxonomyDoesNotExistErrorPayloadObjectTypeDataLoader extends AbstractDictionaryObjectTypeDataLoader
{
    public function getObjectClass(): string
    {
        return TaxonomyDoesNotExistErrorPayload::class;
    }
}
