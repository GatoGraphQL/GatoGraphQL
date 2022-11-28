<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateMutations\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\UserStateMutations\ObjectModels\UserIsLoggedInErrorPayload;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractDictionaryObjectTypeDataLoader;

class UserIsLoggedInErrorPayloadObjectTypeDataLoader extends AbstractDictionaryObjectTypeDataLoader
{
    protected function getObjectClass(): string
    {
        return UserIsLoggedInErrorPayload::class;
    }
}
