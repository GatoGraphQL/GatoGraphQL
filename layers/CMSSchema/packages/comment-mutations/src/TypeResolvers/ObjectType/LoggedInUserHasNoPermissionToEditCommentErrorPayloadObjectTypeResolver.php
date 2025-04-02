<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\CommentMutations\RelationalTypeDataLoaders\ObjectType\LoggedInUserHasNoPermissionToEditCommentErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class LoggedInUserHasNoPermissionToEditCommentErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?LoggedInUserHasNoPermissionToEditCommentErrorPayloadObjectTypeDataLoader $loggedInUserHasNoPermissionToEditCommentErrorPayloadObjectTypeDataLoader = null;

    final protected function getLoggedInUserHasNoPermissionToEditCommentErrorPayloadObjectTypeDataLoader(): LoggedInUserHasNoPermissionToEditCommentErrorPayloadObjectTypeDataLoader
    {
        if ($this->loggedInUserHasNoPermissionToEditCommentErrorPayloadObjectTypeDataLoader === null) {
            /** @var LoggedInUserHasNoPermissionToEditCommentErrorPayloadObjectTypeDataLoader */
            $loggedInUserHasNoPermissionToEditCommentErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(LoggedInUserHasNoPermissionToEditCommentErrorPayloadObjectTypeDataLoader::class);
            $this->loggedInUserHasNoPermissionToEditCommentErrorPayloadObjectTypeDataLoader = $loggedInUserHasNoPermissionToEditCommentErrorPayloadObjectTypeDataLoader;
        }
        return $this->loggedInUserHasNoPermissionToEditCommentErrorPayloadObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'LoggedInUserHasNoPermissionToEditCommentErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The logged-in user has no permission to edit the comment"', 'comment-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getLoggedInUserHasNoPermissionToEditCommentErrorPayloadObjectTypeDataLoader();
    }
}
