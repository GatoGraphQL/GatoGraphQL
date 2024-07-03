<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateMutations\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\UserStateMutations\ObjectModels\InvalidUserEmailErrorPayload;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractDictionaryObjectTypeDataLoader;

class InvalidUserEmailErrorPayloadObjectTypeDataLoader extends AbstractDictionaryObjectTypeDataLoader
{
    public function getObjectClass(): string
    {
        return InvalidUserEmailErrorPayload::class;
    }
}
