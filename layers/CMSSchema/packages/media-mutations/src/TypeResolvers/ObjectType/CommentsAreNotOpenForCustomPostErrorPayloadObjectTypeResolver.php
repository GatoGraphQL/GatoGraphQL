<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\MediaMutations\RelationalTypeDataLoaders\ObjectType\CommentsAreNotOpenForCustomPostErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class CommentsAreNotOpenForCustomPostErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?CommentsAreNotOpenForCustomPostErrorPayloadObjectTypeDataLoader $commentParentCommentDoesNotExistErrorPayloadObjectTypeDataLoader = null;

    final public function setCommentsAreNotOpenForCustomPostErrorPayloadObjectTypeDataLoader(CommentsAreNotOpenForCustomPostErrorPayloadObjectTypeDataLoader $commentParentCommentDoesNotExistErrorPayloadObjectTypeDataLoader): void
    {
        $this->commentParentCommentDoesNotExistErrorPayloadObjectTypeDataLoader = $commentParentCommentDoesNotExistErrorPayloadObjectTypeDataLoader;
    }
    final protected function getCommentsAreNotOpenForCustomPostErrorPayloadObjectTypeDataLoader(): CommentsAreNotOpenForCustomPostErrorPayloadObjectTypeDataLoader
    {
        if ($this->commentParentCommentDoesNotExistErrorPayloadObjectTypeDataLoader === null) {
            /** @var CommentsAreNotOpenForCustomPostErrorPayloadObjectTypeDataLoader */
            $commentParentCommentDoesNotExistErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(CommentsAreNotOpenForCustomPostErrorPayloadObjectTypeDataLoader::class);
            $this->commentParentCommentDoesNotExistErrorPayloadObjectTypeDataLoader = $commentParentCommentDoesNotExistErrorPayloadObjectTypeDataLoader;
        }
        return $this->commentParentCommentDoesNotExistErrorPayloadObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'CommentsAreNotOpenForCustomPostErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "Comments are not open for the custom post"', 'media-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getCommentsAreNotOpenForCustomPostErrorPayloadObjectTypeDataLoader();
    }
}
