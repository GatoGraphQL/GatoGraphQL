<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\CommentMutations\ObjectModels\CommentsAreNotOpenForCustomPostErrorPayload;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractDictionaryObjectTypeDataLoader;

class CommentsAreNotOpenForCustomPostErrorPayloadObjectTypeDataLoader extends AbstractDictionaryObjectTypeDataLoader
{
    protected function getObjectClass(): string
    {
        return CommentsAreNotOpenForCustomPostErrorPayload::class;
    }
}
