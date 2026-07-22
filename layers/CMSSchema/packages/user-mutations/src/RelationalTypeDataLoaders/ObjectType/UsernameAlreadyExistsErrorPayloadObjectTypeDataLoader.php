<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutations\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\UserMutations\ObjectModels\UsernameAlreadyExistsErrorPayload;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractDictionaryObjectTypeDataLoader;

class UsernameAlreadyExistsErrorPayloadObjectTypeDataLoader extends AbstractDictionaryObjectTypeDataLoader
{
    public function getObjectClass(): string
    {
        return UsernameAlreadyExistsErrorPayload::class;
    }
}
