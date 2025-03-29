<?php

declare(strict_types=1);

namespace PoPCMSSchema\MetaMutations\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\MetaMutations\ObjectModels\TermMetaKeyWithValueDoesNotExistErrorPayload;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractDictionaryObjectTypeDataLoader;

class TermMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeDataLoader extends AbstractDictionaryObjectTypeDataLoader
{
    public function getObjectClass(): string
    {
        return TermMetaKeyWithValueDoesNotExistErrorPayload::class;
    }
}
