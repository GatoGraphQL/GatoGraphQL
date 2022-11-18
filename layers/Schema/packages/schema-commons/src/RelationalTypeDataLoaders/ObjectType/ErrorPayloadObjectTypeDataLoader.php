<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\RelationalTypeDataLoaders\ObjectType;

use PoPSchema\SchemaCommons\ObjectModels\ErrorPayload;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractDictionaryObjectTypeDataLoader;

class ErrorPayloadObjectTypeDataLoader extends AbstractDictionaryObjectTypeDataLoader
{
    protected function getObjectClass(): string
    {
        return ErrorPayload::class;
    }
}
