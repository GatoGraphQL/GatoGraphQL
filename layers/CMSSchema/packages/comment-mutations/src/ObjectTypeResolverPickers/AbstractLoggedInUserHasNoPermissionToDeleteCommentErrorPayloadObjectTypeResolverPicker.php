<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CommentMutations\ObjectModels\LoggedInUserHasNoPermissionToDeleteCommentErrorPayload;
use PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType\LoggedInUserHasNoPermissionToDeleteCommentErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractLoggedInUserHasNoPermissionToDeleteCommentErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?LoggedInUserHasNoPermissionToDeleteCommentErrorPayloadObjectTypeResolver $loggedInUserHasNoPermissionToDeleteCommentErrorPayloadObjectTypeResolver = null;

    final protected function getLoggedInUserHasNoPermissionToDeleteCommentErrorPayloadObjectTypeResolver(): LoggedInUserHasNoPermissionToDeleteCommentErrorPayloadObjectTypeResolver
    {
        if ($this->loggedInUserHasNoPermissionToDeleteCommentErrorPayloadObjectTypeResolver === null) {
            /** @var LoggedInUserHasNoPermissionToDeleteCommentErrorPayloadObjectTypeResolver */
            $loggedInUserHasNoPermissionToDeleteCommentErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(LoggedInUserHasNoPermissionToDeleteCommentErrorPayloadObjectTypeResolver::class);
            $this->loggedInUserHasNoPermissionToDeleteCommentErrorPayloadObjectTypeResolver = $loggedInUserHasNoPermissionToDeleteCommentErrorPayloadObjectTypeResolver;
        }
        return $this->loggedInUserHasNoPermissionToDeleteCommentErrorPayloadObjectTypeResolver;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getLoggedInUserHasNoPermissionToDeleteCommentErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return LoggedInUserHasNoPermissionToDeleteCommentErrorPayload::class;
    }
}
