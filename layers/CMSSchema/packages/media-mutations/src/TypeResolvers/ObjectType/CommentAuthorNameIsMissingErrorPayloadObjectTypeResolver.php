<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\MediaMutations\RelationalTypeDataLoaders\ObjectType\CommentAuthorNameIsMissingErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class CommentAuthorNameIsMissingErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?CommentAuthorNameIsMissingErrorPayloadObjectTypeDataLoader $commentAuthorNameIsMissingErrorPayloadObjectTypeDataLoader = null;

    final public function setCommentAuthorNameIsMissingErrorPayloadObjectTypeDataLoader(CommentAuthorNameIsMissingErrorPayloadObjectTypeDataLoader $commentAuthorNameIsMissingErrorPayloadObjectTypeDataLoader): void
    {
        $this->commentAuthorNameIsMissingErrorPayloadObjectTypeDataLoader = $commentAuthorNameIsMissingErrorPayloadObjectTypeDataLoader;
    }
    final protected function getCommentAuthorNameIsMissingErrorPayloadObjectTypeDataLoader(): CommentAuthorNameIsMissingErrorPayloadObjectTypeDataLoader
    {
        if ($this->commentAuthorNameIsMissingErrorPayloadObjectTypeDataLoader === null) {
            /** @var CommentAuthorNameIsMissingErrorPayloadObjectTypeDataLoader */
            $commentAuthorNameIsMissingErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(CommentAuthorNameIsMissingErrorPayloadObjectTypeDataLoader::class);
            $this->commentAuthorNameIsMissingErrorPayloadObjectTypeDataLoader = $commentAuthorNameIsMissingErrorPayloadObjectTypeDataLoader;
        }
        return $this->commentAuthorNameIsMissingErrorPayloadObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'CommentAuthorNameIsMissingErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The comment\'s author name is missing"', 'media-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getCommentAuthorNameIsMissingErrorPayloadObjectTypeDataLoader();
    }
}
