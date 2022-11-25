<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CommentMutations\ObjectModels\CommentCustomPostIDIsMissingErrorPayload;
use PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType\CommentCustomPostIDIsMissingErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractCommentCustomPostIDIsMissingErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?CommentCustomPostIDIsMissingErrorPayloadObjectTypeResolver $commentCustomPostIDIsMissingErrorPayloadObjectTypeResolver = null;

    final public function setCommentCustomPostIDIsMissingErrorPayloadObjectTypeResolver(CommentCustomPostIDIsMissingErrorPayloadObjectTypeResolver $commentCustomPostIDIsMissingErrorPayloadObjectTypeResolver): void
    {
        $this->commentCustomPostIDIsMissingErrorPayloadObjectTypeResolver = $commentCustomPostIDIsMissingErrorPayloadObjectTypeResolver;
    }
    final protected function getCommentCustomPostIDIsMissingErrorPayloadObjectTypeResolver(): CommentCustomPostIDIsMissingErrorPayloadObjectTypeResolver
    {
        /** @var CommentCustomPostIDIsMissingErrorPayloadObjectTypeResolver */
        return $this->commentCustomPostIDIsMissingErrorPayloadObjectTypeResolver ??= $this->instanceManager->getInstance(CommentCustomPostIDIsMissingErrorPayloadObjectTypeResolver::class);
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getCommentCustomPostIDIsMissingErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return CommentCustomPostIDIsMissingErrorPayload::class;
    }
}
