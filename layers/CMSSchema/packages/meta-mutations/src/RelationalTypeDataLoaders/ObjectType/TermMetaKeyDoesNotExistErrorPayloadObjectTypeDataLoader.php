<?php

declare(strict_types=1);

namespace PoPCMSSchema\MetaMutations\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\MetaMutations\ObjectModels\TermMetaKeyDoesNotExistErrorPayload;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractDictionaryObjectTypeDataLoader;

class TermMetaKeyDoesNotExistErrorPayloadObjectTypeDataLoader extends AbstractDictionaryObjectTypeDataLoader
{
    public function getObjectClass(): string
    {
        return TermMetaKeyDoesNotExistErrorPayload::class;
    }
}
