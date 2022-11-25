<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\CommentMutations\RelationalTypeDataLoaders\ObjectType\CommentAuthorNameIsMissingErrorPayloadObjectTypeDataLoader;
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
        /** @var CommentAuthorNameIsMissingErrorPayloadObjectTypeDataLoader */
        return $this->commentAuthorNameIsMissingErrorPayloadObjectTypeDataLoader ??= $this->instanceManager->getInstance(CommentAuthorNameIsMissingErrorPayloadObjectTypeDataLoader::class);
    }

    public function getTypeName(): string
    {
        return 'CommentAuthorNameIsMissingErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The comment\'s author name is missing"', 'comment-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getCommentAuthorNameIsMissingErrorPayloadObjectTypeDataLoader();
    }
}
