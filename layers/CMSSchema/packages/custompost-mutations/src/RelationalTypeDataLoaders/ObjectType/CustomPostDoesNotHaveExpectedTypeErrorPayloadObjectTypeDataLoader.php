<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\CustomPostMutations\ObjectModels\CustomPostDoesNotHaveExpectedTypeErrorPayload;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractDictionaryObjectTypeDataLoader;

class CustomPostDoesNotHaveExpectedTypeErrorPayloadObjectTypeDataLoader extends AbstractDictionaryObjectTypeDataLoader
{
    public function getObjectClass(): string
    {
        return CustomPostDoesNotHaveExpectedTypeErrorPayload::class;
    }
}
