<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CommentMutations\ObjectModels\CommentContentIsMissingErrorPayload;
use PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType\CommentContentIsMissingErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractCommentContentIsMissingErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?CommentContentIsMissingErrorPayloadObjectTypeResolver $commentContentIsMissingErrorPayloadObjectTypeResolver = null;

    final public function setCommentContentIsMissingErrorPayloadObjectTypeResolver(CommentContentIsMissingErrorPayloadObjectTypeResolver $commentContentIsMissingErrorPayloadObjectTypeResolver): void
    {
        $this->commentContentIsMissingErrorPayloadObjectTypeResolver = $commentContentIsMissingErrorPayloadObjectTypeResolver;
    }
    final protected function getCommentContentIsMissingErrorPayloadObjectTypeResolver(): CommentContentIsMissingErrorPayloadObjectTypeResolver
    {
        /** @var CommentContentIsMissingErrorPayloadObjectTypeResolver */
        return $this->commentContentIsMissingErrorPayloadObjectTypeResolver ??= $this->instanceManager->getInstance(CommentContentIsMissingErrorPayloadObjectTypeResolver::class);
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getCommentContentIsMissingErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return CommentContentIsMissingErrorPayload::class;
    }
}
