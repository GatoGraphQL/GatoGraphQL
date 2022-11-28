<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateMutations\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\UserStateMutations\ObjectModels\PasswordIsIncorrectErrorPayload;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractDictionaryObjectTypeDataLoader;

class PasswordIsIncorrectErrorPayloadObjectTypeDataLoader extends AbstractDictionaryObjectTypeDataLoader
{
    protected function getObjectClass(): string
    {
        return PasswordIsIncorrectErrorPayload::class;
    }
}
