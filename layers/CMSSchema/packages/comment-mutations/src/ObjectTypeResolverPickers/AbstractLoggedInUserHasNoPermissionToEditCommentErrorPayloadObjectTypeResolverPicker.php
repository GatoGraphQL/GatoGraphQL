<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CommentMutations\ObjectModels\LoggedInUserHasNoPermissionToEditCommentErrorPayload;
use PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType\LoggedInUserHasNoPermissionToEditCommentErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractLoggedInUserHasNoPermissionToEditCommentErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?LoggedInUserHasNoPermissionToEditCommentErrorPayloadObjectTypeResolver $loggedInUserHasNoPermissionToEditComment = null;

    final protected function getLoggedInUserHasNoPermissionToEditCommentErrorPayloadObjectTypeResolver(): LoggedInUserHasNoPermissionToEditCommentErrorPayloadObjectTypeResolver
    {
        if ($this->loggedInUserHasNoPermissionToEditComment === null) {
            /** @var LoggedInUserHasNoPermissionToEditCommentErrorPayloadObjectTypeResolver */
            $loggedInUserHasNoPermissionToEditComment = $this->instanceManager->getInstance(LoggedInUserHasNoPermissionToEditCommentErrorPayloadObjectTypeResolver::class);
            $this->loggedInUserHasNoPermissionToEditComment = $loggedInUserHasNoPermissionToEditComment;
        }
        return $this->loggedInUserHasNoPermissionToEditComment;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getLoggedInUserHasNoPermissionToEditCommentErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return LoggedInUserHasNoPermissionToEditCommentErrorPayload::class;
    }
}
