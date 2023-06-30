<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CommentMutations\ObjectModels\CommentParentCommentDoesNotExistErrorPayload;
use PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType\CommentParentCommentDoesNotExistErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractCommentParentCommentDoesNotExistMutationErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?CommentParentCommentDoesNotExistErrorPayloadObjectTypeResolver $commentParentCommentDoesNotExistErrorPayloadObjectTypeResolver = null;

    final public function setCommentParentCommentDoesNotExistErrorPayloadObjectTypeResolver(CommentParentCommentDoesNotExistErrorPayloadObjectTypeResolver $commentParentCommentDoesNotExistErrorPayloadObjectTypeResolver): void
    {
        $this->commentParentCommentDoesNotExistErrorPayloadObjectTypeResolver = $commentParentCommentDoesNotExistErrorPayloadObjectTypeResolver;
    }
    final protected function getCommentParentCommentDoesNotExistErrorPayloadObjectTypeResolver(): CommentParentCommentDoesNotExistErrorPayloadObjectTypeResolver
    {
        if ($this->commentParentCommentDoesNotExistErrorPayloadObjectTypeResolver === null) {
            /** @var CommentParentCommentDoesNotExistErrorPayloadObjectTypeResolver */
            $commentParentCommentDoesNotExistErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(CommentParentCommentDoesNotExistErrorPayloadObjectTypeResolver::class);
            $this->commentParentCommentDoesNotExistErrorPayloadObjectTypeResolver = $commentParentCommentDoesNotExistErrorPayloadObjectTypeResolver;
        }
        return $this->commentParentCommentDoesNotExistErrorPayloadObjectTypeResolver;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getCommentParentCommentDoesNotExistErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return CommentParentCommentDoesNotExistErrorPayload::class;
    }
}
