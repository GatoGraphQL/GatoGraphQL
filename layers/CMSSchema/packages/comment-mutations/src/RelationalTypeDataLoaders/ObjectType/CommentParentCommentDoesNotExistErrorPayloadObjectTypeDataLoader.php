<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\CommentMutations\ObjectModels\CommentParentCommentDoesNotExistErrorPayload;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractDictionaryObjectTypeDataLoader;

class CommentParentCommentDoesNotExistErrorPayloadObjectTypeDataLoader extends AbstractDictionaryObjectTypeDataLoader
{
    protected function getObjectClass(): string
    {
        return CommentParentCommentDoesNotExistErrorPayload::class;
    }
}
