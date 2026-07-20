<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\CustomPostMutations\ObjectModels\CustomPostDoesNotSupportTrashErrorPayload;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractDictionaryObjectTypeDataLoader;

class CustomPostDoesNotSupportTrashErrorPayloadObjectTypeDataLoader extends AbstractDictionaryObjectTypeDataLoader
{
    public function getObjectClass(): string
    {
        return CustomPostDoesNotSupportTrashErrorPayload::class;
    }
}
