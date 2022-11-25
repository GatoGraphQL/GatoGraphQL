<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\CommentMutations\RelationalTypeDataLoaders\ObjectType\CommentParentCommentDoesNotExistErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class CommentParentCommentDoesNotExistErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?CommentParentCommentDoesNotExistErrorPayloadObjectTypeDataLoader $commentParentCommentDoesNotExistErrorPayloadObjectTypeDataLoader = null;

    final public function setCommentParentCommentDoesNotExistErrorPayloadObjectTypeDataLoader(CommentParentCommentDoesNotExistErrorPayloadObjectTypeDataLoader $commentParentCommentDoesNotExistErrorPayloadObjectTypeDataLoader): void
    {
        $this->commentParentCommentDoesNotExistErrorPayloadObjectTypeDataLoader = $commentParentCommentDoesNotExistErrorPayloadObjectTypeDataLoader;
    }
    final protected function getCommentParentCommentDoesNotExistErrorPayloadObjectTypeDataLoader(): CommentParentCommentDoesNotExistErrorPayloadObjectTypeDataLoader
    {
        /** @var CommentParentCommentDoesNotExistErrorPayloadObjectTypeDataLoader */
        return $this->commentParentCommentDoesNotExistErrorPayloadObjectTypeDataLoader ??= $this->instanceManager->getInstance(CommentParentCommentDoesNotExistErrorPayloadObjectTypeDataLoader::class);
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
