<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\CommentMutations\RelationalTypeDataLoaders\ObjectType\LoggedInUserHasNoPermissionToDeleteCommentErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class LoggedInUserHasNoPermissionToDeleteCommentErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?LoggedInUserHasNoPermissionToDeleteCommentErrorPayloadObjectTypeDataLoader $loggedInUserHasNoPermissionToDeleteCommentErrorPayloadObjectTypeDataLoader = null;

    final protected function getLoggedInUserHasNoPermissionToDeleteCommentErrorPayloadObjectTypeDataLoader(): LoggedInUserHasNoPermissionToDeleteCommentErrorPayloadObjectTypeDataLoader
    {
        if ($this->loggedInUserHasNoPermissionToDeleteCommentErrorPayloadObjectTypeDataLoader === null) {
            /** @var LoggedInUserHasNoPermissionToDeleteCommentErrorPayloadObjectTypeDataLoader */
            $loggedInUserHasNoPermissionToDeleteCommentErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(LoggedInUserHasNoPermissionToDeleteCommentErrorPayloadObjectTypeDataLoader::class);
            $this->loggedInUserHasNoPermissionToDeleteCommentErrorPayloadObjectTypeDataLoader = $loggedInUserHasNoPermissionToDeleteCommentErrorPayloadObjectTypeDataLoader;
        }
        return $this->loggedInUserHasNoPermissionToDeleteCommentErrorPayloadObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'LoggedInUserHasNoPermissionToDeleteCommentErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The user has no permission to delete the comment"', 'gatographql');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getLoggedInUserHasNoPermissionToDeleteCommentErrorPayloadObjectTypeDataLoader();
    }
}
