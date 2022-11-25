<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\CommentMutations\RelationalTypeDataLoaders\ObjectType\CommentAuthorEmailIsMissingErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class CommentAuthorEmailIsMissingErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?CommentAuthorEmailIsMissingErrorPayloadObjectTypeDataLoader $commentAuthorEmailIsMissingErrorPayloadObjectTypeDataLoader = null;

    final public function setCommentAuthorEmailIsMissingErrorPayloadObjectTypeDataLoader(CommentAuthorEmailIsMissingErrorPayloadObjectTypeDataLoader $commentAuthorEmailIsMissingErrorPayloadObjectTypeDataLoader): void
    {
        $this->commentAuthorEmailIsMissingErrorPayloadObjectTypeDataLoader = $commentAuthorEmailIsMissingErrorPayloadObjectTypeDataLoader;
    }
    final protected function getCommentAuthorEmailIsMissingErrorPayloadObjectTypeDataLoader(): CommentAuthorEmailIsMissingErrorPayloadObjectTypeDataLoader
    {
        /** @var CommentAuthorEmailIsMissingErrorPayloadObjectTypeDataLoader */
        return $this->commentAuthorEmailIsMissingErrorPayloadObjectTypeDataLoader ??= $this->instanceManager->getInstance(CommentAuthorEmailIsMissingErrorPayloadObjectTypeDataLoader::class);
    }

    public function getTypeName(): string
    {
        return 'CommentAuthorEmailIsMissingErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The logged-in user has no permission to edit custom posts"', 'customposts');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getCommentAuthorEmailIsMissingErrorPayloadObjectTypeDataLoader();
    }
}
