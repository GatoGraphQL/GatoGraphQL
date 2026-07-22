<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutations\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\UserMutations\ObjectModels\CannotDeleteYourselfErrorPayload;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractDictionaryObjectTypeDataLoader;

class CannotDeleteYourselfErrorPayloadObjectTypeDataLoader extends AbstractDictionaryObjectTypeDataLoader
{
    public function getObjectClass(): string
    {
        return CannotDeleteYourselfErrorPayload::class;
    }
}
