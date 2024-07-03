<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\RelationalTypeDataLoaders\ObjectType;

use PoPSchema\SchemaCommons\ObjectModels\GenericErrorPayload;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractDictionaryObjectTypeDataLoader;

class GenericErrorPayloadObjectTypeDataLoader extends AbstractDictionaryObjectTypeDataLoader
{
    public function getObjectClass(): string
    {
        return GenericErrorPayload::class;
    }
}
