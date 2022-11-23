<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\RelationalTypeDataLoaders\ObjectType;

use PoPSchema\SchemaCommons\ObjectModels\ObjectMutationPayload;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractDictionaryObjectTypeDataLoader;

class ObjectMutationPayloadObjectTypeDataLoader extends AbstractDictionaryObjectTypeDataLoader
{
    protected function getObjectClass(): string
    {
        return ObjectMutationPayload::class;
    }
}
