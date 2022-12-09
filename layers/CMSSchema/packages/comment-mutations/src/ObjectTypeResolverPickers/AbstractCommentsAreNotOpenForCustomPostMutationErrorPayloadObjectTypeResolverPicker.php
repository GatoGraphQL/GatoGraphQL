<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CommentMutations\ObjectModels\CommentsAreNotOpenForCustomPostErrorPayload;
use PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType\CommentsAreNotOpenForCustomPostErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractCommentsAreNotOpenForCustomPostMutationErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?CommentsAreNotOpenForCustomPostErrorPayloadObjectTypeResolver $commentsAreNotOpenForCustomPostErrorPayloadObjectTypeResolver = null;

    final public function setCommentsAreNotOpenForCustomPostErrorPayloadObjectTypeResolver(CommentsAreNotOpenForCustomPostErrorPayloadObjectTypeResolver $commentsAreNotOpenForCustomPostErrorPayloadObjectTypeResolver): void
    {
        $this->commentsAreNotOpenForCustomPostErrorPayloadObjectTypeResolver = $commentsAreNotOpenForCustomPostErrorPayloadObjectTypeResolver;
    }
    final protected function getCommentsAreNotOpenForCustomPostErrorPayloadObjectTypeResolver(): CommentsAreNotOpenForCustomPostErrorPayloadObjectTypeResolver
    {
        /** @var CommentsAreNotOpenForCustomPostErrorPayloadObjectTypeResolver */
        return $this->commentsAreNotOpenForCustomPostErrorPayloadObjectTypeResolver ??= $this->instanceManager->getInstance(CommentsAreNotOpenForCustomPostErrorPayloadObjectTypeResolver::class);
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getCommentsAreNotOpenForCustomPostErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return CommentsAreNotOpenForCustomPostErrorPayload::class;
    }
}
