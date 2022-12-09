<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\CommentMutations\ObjectModels\CommentsAreNotSupportedByCustomPostTypeErrorPayload;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractDictionaryObjectTypeDataLoader;

class CommentsAreNotSupportedByCustomPostTypeErrorPayloadObjectTypeDataLoader extends AbstractDictionaryObjectTypeDataLoader
{
    protected function getObjectClass(): string
    {
        return CommentsAreNotSupportedByCustomPostTypeErrorPayload::class;
    }
}
