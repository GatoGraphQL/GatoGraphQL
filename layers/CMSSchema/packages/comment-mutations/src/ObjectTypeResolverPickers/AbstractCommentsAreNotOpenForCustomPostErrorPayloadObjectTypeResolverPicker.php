<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CommentMutations\ObjectModels\CommentsAreNotOpenForCustomPostErrorPayload;
use PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType\CommentsAreNotOpenForCustomPostErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractCommentsAreNotOpenForCustomPostErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?CommentsAreNotOpenForCustomPostErrorPayloadObjectTypeResolver $commentsAreNotOpenForCustomPostErrorPayloadObjectTypeResolver = null;

    final public function setCommentsAreNotOpenForCustomPostErrorPayloadObjectTypeResolver(CommentsAreNotOpenForCustomPostErrorPayloadObjectTypeResolver $commentsAreNotOpenForCustomPostErrorPayloadObjectTypeResolver): void
    {
        $this->commentsAreNotOpenForCustomPostErrorPayloadObjectTypeResolver = $commentsAreNotOpenForCustomPostErrorPayloadObjectTypeResolver;
    }
    final protected function getCommentsAreNotOpenForCustomPostErrorPayloadObjectTypeResolver(): CommentsAreNotOpenForCustomPostErrorPayloadObjectTypeResolver
    {
        if ($this->commentsAreNotOpenForCustomPostErrorPayloadObjectTypeResolver === null) {
            /** @var CommentsAreNotOpenForCustomPostErrorPayloadObjectTypeResolver */
            $commentsAreNotOpenForCustomPostErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(CommentsAreNotOpenForCustomPostErrorPayloadObjectTypeResolver::class);
            $this->commentsAreNotOpenForCustomPostErrorPayloadObjectTypeResolver = $commentsAreNotOpenForCustomPostErrorPayloadObjectTypeResolver;
        }
        return $this->commentsAreNotOpenForCustomPostErrorPayloadObjectTypeResolver;
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
