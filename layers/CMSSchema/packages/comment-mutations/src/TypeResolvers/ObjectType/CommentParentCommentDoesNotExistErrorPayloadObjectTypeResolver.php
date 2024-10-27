<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\CommentMutations\RelationalTypeDataLoaders\ObjectType\CommentParentCommentDoesNotExistErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class CommentParentCommentDoesNotExistErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?CommentParentCommentDoesNotExistErrorPayloadObjectTypeDataLoader $commentParentCommentDoesNotExistErrorPayloadObjectTypeDataLoader = null;

    final protected function getCommentParentCommentDoesNotExistErrorPayloadObjectTypeDataLoader(): CommentParentCommentDoesNotExistErrorPayloadObjectTypeDataLoader
    {
        if ($this->commentParentCommentDoesNotExistErrorPayloadObjectTypeDataLoader === null) {
            /** @var CommentParentCommentDoesNotExistErrorPayloadObjectTypeDataLoader */
            $commentParentCommentDoesNotExistErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(CommentParentCommentDoesNotExistErrorPayloadObjectTypeDataLoader::class);
            $this->commentParentCommentDoesNotExistErrorPayloadObjectTypeDataLoader = $commentParentCommentDoesNotExistErrorPayloadObjectTypeDataLoader;
        }
        return $this->commentParentCommentDoesNotExistErrorPayloadObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'CommentParentCommentDoesNotExistErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The comment\'s parent does not exist"', 'comment-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getCommentParentCommentDoesNotExistErrorPayloadObjectTypeDataLoader();
    }
}
