<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\CommentMutations\RelationalTypeDataLoaders\ObjectType\CommentsAreNotSupportedByCustomPostTypeErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class CommentsAreNotSupportedByCustomPostTypeErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?CommentsAreNotSupportedByCustomPostTypeErrorPayloadObjectTypeDataLoader $commentsAreNotSupportedByCustomPostTypeErrorPayloadObjectTypeDataLoader = null;

    final public function setCommentsAreNotSupportedByCustomPostTypeErrorPayloadObjectTypeDataLoader(CommentsAreNotSupportedByCustomPostTypeErrorPayloadObjectTypeDataLoader $commentsAreNotSupportedByCustomPostTypeErrorPayloadObjectTypeDataLoader): void
    {
        $this->commentsAreNotSupportedByCustomPostTypeErrorPayloadObjectTypeDataLoader = $commentsAreNotSupportedByCustomPostTypeErrorPayloadObjectTypeDataLoader;
    }
    final protected function getCommentsAreNotSupportedByCustomPostTypeErrorPayloadObjectTypeDataLoader(): CommentsAreNotSupportedByCustomPostTypeErrorPayloadObjectTypeDataLoader
    {
        /** @var CommentsAreNotSupportedByCustomPostTypeErrorPayloadObjectTypeDataLoader */
        return $this->commentsAreNotSupportedByCustomPostTypeErrorPayloadObjectTypeDataLoader ??= $this->instanceManager->getInstance(CommentsAreNotSupportedByCustomPostTypeErrorPayloadObjectTypeDataLoader::class);
    }

    public function getTypeName(): string
    {
        return 'CommentsAreNotSupportedByCustomPostTypeErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "Comments are not supported by the custom post type"', 'comment-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getCommentsAreNotSupportedByCustomPostTypeErrorPayloadObjectTypeDataLoader();
    }
}
