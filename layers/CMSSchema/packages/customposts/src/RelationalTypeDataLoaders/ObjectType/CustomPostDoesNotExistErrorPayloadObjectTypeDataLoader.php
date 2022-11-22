<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\CustomPosts\ObjectModels\CustomPostDoesNotExistErrorPayload;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractDictionaryObjectTypeDataLoader;

class CustomPostDoesNotExistErrorPayloadObjectTypeDataLoader extends AbstractDictionaryObjectTypeDataLoader
{
    protected function getObjectClass(): string
    {
        return CustomPostDoesNotExistErrorPayload::class;
    }
}
