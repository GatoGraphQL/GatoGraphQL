<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\CommentMutations\RelationalTypeDataLoaders\ObjectType\CommentContentIsMissingErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class CommentContentIsMissingErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?CommentContentIsMissingErrorPayloadObjectTypeDataLoader $commentContentIsMissingErrorPayloadObjectTypeDataLoader = null;

    final public function setCommentContentIsMissingErrorPayloadObjectTypeDataLoader(CommentContentIsMissingErrorPayloadObjectTypeDataLoader $commentContentIsMissingErrorPayloadObjectTypeDataLoader): void
    {
        $this->commentContentIsMissingErrorPayloadObjectTypeDataLoader = $commentContentIsMissingErrorPayloadObjectTypeDataLoader;
    }
    final protected function getCommentContentIsMissingErrorPayloadObjectTypeDataLoader(): CommentContentIsMissingErrorPayloadObjectTypeDataLoader
    {
        /** @var CommentContentIsMissingErrorPayloadObjectTypeDataLoader */
        return $this->commentContentIsMissingErrorPayloadObjectTypeDataLoader ??= $this->instanceManager->getInstance(CommentContentIsMissingErrorPayloadObjectTypeDataLoader::class);
    }

    public function getTypeName(): string
    {
        return 'CommentContentIsMissingErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The comment\'s content is missing"', 'comment-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getCommentContentIsMissingErrorPayloadObjectTypeDataLoader();
    }
}
