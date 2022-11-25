<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\CommentMutations\ObjectModels\CommentContentIsMissingErrorPayload;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractDictionaryObjectTypeDataLoader;

class CommentContentIsMissingErrorPayloadObjectTypeDataLoader extends AbstractDictionaryObjectTypeDataLoader
{
    protected function getObjectClass(): string
    {
        return CommentContentIsMissingErrorPayload::class;
    }
}
