<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutations\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\UserMutations\ObjectModels\EmailAlreadyExistsErrorPayload;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractDictionaryObjectTypeDataLoader;

class EmailAlreadyExistsErrorPayloadObjectTypeDataLoader extends AbstractDictionaryObjectTypeDataLoader
{
    public function getObjectClass(): string
    {
        return EmailAlreadyExistsErrorPayload::class;
    }
}
