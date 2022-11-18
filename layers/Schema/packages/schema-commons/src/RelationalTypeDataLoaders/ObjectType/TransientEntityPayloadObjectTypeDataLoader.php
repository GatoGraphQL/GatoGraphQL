<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\RelationalTypeDataLoaders\ObjectType;

use PoPSchema\SchemaCommons\ObjectModels\MutationPayload;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractDictionaryObjectTypeDataLoader;

class TransientEntityPayloadObjectTypeDataLoader extends AbstractDictionaryObjectTypeDataLoader
{
    protected function getObjectClass(): string
    {
        return MutationPayload::class;
    }
}
