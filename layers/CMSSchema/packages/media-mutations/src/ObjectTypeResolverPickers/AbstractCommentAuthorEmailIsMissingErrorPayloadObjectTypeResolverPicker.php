<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\MediaMutations\ObjectModels\CommentAuthorEmailIsMissingErrorPayload;
use PoPCMSSchema\MediaMutations\TypeResolvers\ObjectType\CommentAuthorEmailIsMissingErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractCommentAuthorEmailIsMissingErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?CommentAuthorEmailIsMissingErrorPayloadObjectTypeResolver $commentAuthorEmailIsMissingErrorPayloadObjectTypeResolver = null;

    final public function setCommentAuthorEmailIsMissingErrorPayloadObjectTypeResolver(CommentAuthorEmailIsMissingErrorPayloadObjectTypeResolver $commentAuthorEmailIsMissingErrorPayloadObjectTypeResolver): void
    {
        $this->commentAuthorEmailIsMissingErrorPayloadObjectTypeResolver = $commentAuthorEmailIsMissingErrorPayloadObjectTypeResolver;
    }
    final protected function getCommentAuthorEmailIsMissingErrorPayloadObjectTypeResolver(): CommentAuthorEmailIsMissingErrorPayloadObjectTypeResolver
    {
        if ($this->commentAuthorEmailIsMissingErrorPayloadObjectTypeResolver === null) {
            /** @var CommentAuthorEmailIsMissingErrorPayloadObjectTypeResolver */
            $commentAuthorEmailIsMissingErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(CommentAuthorEmailIsMissingErrorPayloadObjectTypeResolver::class);
            $this->commentAuthorEmailIsMissingErrorPayloadObjectTypeResolver = $commentAuthorEmailIsMissingErrorPayloadObjectTypeResolver;
        }
        return $this->commentAuthorEmailIsMissingErrorPayloadObjectTypeResolver;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getCommentAuthorEmailIsMissingErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return CommentAuthorEmailIsMissingErrorPayload::class;
    }
}
