<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMetaMutations\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\TaxonomyMetaMutations\ObjectModels\TaxonomyTermDoesNotExistErrorPayload;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractDictionaryObjectTypeDataLoader;

class TaxonomyTermDoesNotExistErrorPayloadObjectTypeDataLoader extends AbstractDictionaryObjectTypeDataLoader
{
    public function getObjectClass(): string
    {
        return TaxonomyTermDoesNotExistErrorPayload::class;
    }
}
