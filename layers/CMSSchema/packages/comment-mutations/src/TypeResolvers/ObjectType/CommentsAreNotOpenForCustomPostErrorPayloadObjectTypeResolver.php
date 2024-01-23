<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\CommentMutations\RelationalTypeDataLoaders\ObjectType\CommentsAreNotOpenForCustomPostErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class CommentsAreNotOpenForCustomPostErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?CommentsAreNotOpenForCustomPostErrorPayloadObjectTypeDataLoader $commentsAreNotOpenForCustomPostErrorPayloadObjectTypeDataLoader = null;

    final public function setCommentsAreNotOpenForCustomPostErrorPayloadObjectTypeDataLoader(CommentsAreNotOpenForCustomPostErrorPayloadObjectTypeDataLoader $commentsAreNotOpenForCustomPostErrorPayloadObjectTypeDataLoader): void
    {
        $this->commentsAreNotOpenForCustomPostErrorPayloadObjectTypeDataLoader = $commentsAreNotOpenForCustomPostErrorPayloadObjectTypeDataLoader;
    }
    final protected function getCommentsAreNotOpenForCustomPostErrorPayloadObjectTypeDataLoader(): CommentsAreNotOpenForCustomPostErrorPayloadObjectTypeDataLoader
    {
        if ($this->commentsAreNotOpenForCustomPostErrorPayloadObjectTypeDataLoader === null) {
            /** @var CommentsAreNotOpenForCustomPostErrorPayloadObjectTypeDataLoader */
            $commentsAreNotOpenForCustomPostErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(CommentsAreNotOpenForCustomPostErrorPayloadObjectTypeDataLoader::class);
            $this->commentsAreNotOpenForCustomPostErrorPayloadObjectTypeDataLoader = $commentsAreNotOpenForCustomPostErrorPayloadObjectTypeDataLoader;
        }
        return $this->commentsAreNotOpenForCustomPostErrorPayloadObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'CommentsAreNotOpenForCustomPostErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "Comments are not open for the custom post"', 'comment-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getCommentsAreNotOpenForCustomPostErrorPayloadObjectTypeDataLoader();
    }
}
