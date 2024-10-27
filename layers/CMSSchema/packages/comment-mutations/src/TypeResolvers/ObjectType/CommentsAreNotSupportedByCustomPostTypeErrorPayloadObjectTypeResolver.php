<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\CommentMutations\RelationalTypeDataLoaders\ObjectType\CommentsAreNotSupportedByCustomPostTypeErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class CommentsAreNotSupportedByCustomPostTypeErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?CommentsAreNotSupportedByCustomPostTypeErrorPayloadObjectTypeDataLoader $commentsAreNotSupportedByCustomPostTypeErrorPayloadObjectTypeDataLoader = null;

    final protected function getCommentsAreNotSupportedByCustomPostTypeErrorPayloadObjectTypeDataLoader(): CommentsAreNotSupportedByCustomPostTypeErrorPayloadObjectTypeDataLoader
    {
        if ($this->commentsAreNotSupportedByCustomPostTypeErrorPayloadObjectTypeDataLoader === null) {
            /** @var CommentsAreNotSupportedByCustomPostTypeErrorPayloadObjectTypeDataLoader */
            $commentsAreNotSupportedByCustomPostTypeErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(CommentsAreNotSupportedByCustomPostTypeErrorPayloadObjectTypeDataLoader::class);
            $this->commentsAreNotSupportedByCustomPostTypeErrorPayloadObjectTypeDataLoader = $commentsAreNotSupportedByCustomPostTypeErrorPayloadObjectTypeDataLoader;
        }
        return $this->commentsAreNotSupportedByCustomPostTypeErrorPayloadObjectTypeDataLoader;
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
