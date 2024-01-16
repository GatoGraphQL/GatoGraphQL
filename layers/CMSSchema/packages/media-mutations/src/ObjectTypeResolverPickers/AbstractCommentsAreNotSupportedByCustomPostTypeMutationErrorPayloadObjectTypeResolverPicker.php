<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\MediaMutations\ObjectModels\CommentsAreNotSupportedByCustomPostTypeErrorPayload;
use PoPCMSSchema\MediaMutations\TypeResolvers\ObjectType\CommentsAreNotSupportedByCustomPostTypeErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractCommentsAreNotSupportedByCustomPostTypeMutationErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?CommentsAreNotSupportedByCustomPostTypeErrorPayloadObjectTypeResolver $commentsAreNotSupportedByCustomPostTypeErrorPayloadObjectTypeResolver = null;

    final public function setCommentsAreNotSupportedByCustomPostTypeErrorPayloadObjectTypeResolver(CommentsAreNotSupportedByCustomPostTypeErrorPayloadObjectTypeResolver $commentsAreNotSupportedByCustomPostTypeErrorPayloadObjectTypeResolver): void
    {
        $this->commentsAreNotSupportedByCustomPostTypeErrorPayloadObjectTypeResolver = $commentsAreNotSupportedByCustomPostTypeErrorPayloadObjectTypeResolver;
    }
    final protected function getCommentsAreNotSupportedByCustomPostTypeErrorPayloadObjectTypeResolver(): CommentsAreNotSupportedByCustomPostTypeErrorPayloadObjectTypeResolver
    {
        if ($this->commentsAreNotSupportedByCustomPostTypeErrorPayloadObjectTypeResolver === null) {
            /** @var CommentsAreNotSupportedByCustomPostTypeErrorPayloadObjectTypeResolver */
            $commentsAreNotSupportedByCustomPostTypeErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(CommentsAreNotSupportedByCustomPostTypeErrorPayloadObjectTypeResolver::class);
            $this->commentsAreNotSupportedByCustomPostTypeErrorPayloadObjectTypeResolver = $commentsAreNotSupportedByCustomPostTypeErrorPayloadObjectTypeResolver;
        }
        return $this->commentsAreNotSupportedByCustomPostTypeErrorPayloadObjectTypeResolver;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getCommentsAreNotSupportedByCustomPostTypeErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return CommentsAreNotSupportedByCustomPostTypeErrorPayload::class;
    }
}
