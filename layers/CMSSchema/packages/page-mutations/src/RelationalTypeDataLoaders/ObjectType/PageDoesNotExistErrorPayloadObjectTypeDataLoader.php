<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMutations\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\PageMutations\ObjectModels\PageDoesNotExistErrorPayload;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractDictionaryObjectTypeDataLoader;

class PageDoesNotExistErrorPayloadObjectTypeDataLoader extends AbstractDictionaryObjectTypeDataLoader
{
    protected function getObjectClass(): string
    {
        return PageDoesNotExistErrorPayload::class;
    }
}
