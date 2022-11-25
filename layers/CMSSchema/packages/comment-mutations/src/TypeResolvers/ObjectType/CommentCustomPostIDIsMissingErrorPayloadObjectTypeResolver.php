<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\CommentMutations\RelationalTypeDataLoaders\ObjectType\CommentCustomPostIDIsMissingErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class CommentCustomPostIDIsMissingErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?CommentCustomPostIDIsMissingErrorPayloadObjectTypeDataLoader $commentCustomPostIDIsMissingErrorPayloadObjectTypeDataLoader = null;

    final public function setCommentCustomPostIDIsMissingErrorPayloadObjectTypeDataLoader(CommentCustomPostIDIsMissingErrorPayloadObjectTypeDataLoader $commentCustomPostIDIsMissingErrorPayloadObjectTypeDataLoader): void
    {
        $this->commentCustomPostIDIsMissingErrorPayloadObjectTypeDataLoader = $commentCustomPostIDIsMissingErrorPayloadObjectTypeDataLoader;
    }
    final protected function getCommentCustomPostIDIsMissingErrorPayloadObjectTypeDataLoader(): CommentCustomPostIDIsMissingErrorPayloadObjectTypeDataLoader
    {
        /** @var CommentCustomPostIDIsMissingErrorPayloadObjectTypeDataLoader */
        return $this->commentCustomPostIDIsMissingErrorPayloadObjectTypeDataLoader ??= $this->instanceManager->getInstance(CommentCustomPostIDIsMissingErrorPayloadObjectTypeDataLoader::class);
    }

    public function getTypeName(): string
    {
        return 'CommentCustomPostIDIsMissingErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The comment\'s custom post ID is missing"', 'comment-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getCommentCustomPostIDIsMissingErrorPayloadObjectTypeDataLoader();
    }
}
