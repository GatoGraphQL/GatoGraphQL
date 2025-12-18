<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\MenuMutations\ObjectModels\MenuDoesNotExistErrorPayload;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractDictionaryObjectTypeDataLoader;

class MenuDoesNotExistErrorPayloadObjectTypeDataLoader extends AbstractDictionaryObjectTypeDataLoader
{
    public function getObjectClass(): string
    {
        return MenuDoesNotExistErrorPayload::class;
    }
}
