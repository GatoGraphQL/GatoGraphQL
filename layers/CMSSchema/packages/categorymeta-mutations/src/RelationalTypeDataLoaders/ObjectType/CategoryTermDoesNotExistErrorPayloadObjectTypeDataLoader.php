<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMetaMutations\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\CategoryMetaMutations\ObjectModels\CategoryTermDoesNotExistErrorPayload;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractDictionaryObjectTypeDataLoader;

class CategoryTermDoesNotExistErrorPayloadObjectTypeDataLoader extends AbstractDictionaryObjectTypeDataLoader
{
    public function getObjectClass(): string
    {
        return CategoryTermDoesNotExistErrorPayload::class;
    }
}
