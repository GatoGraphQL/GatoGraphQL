<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CommentMutations\ObjectModels\CommentHasAlreadyBeenTrashedErrorPayload;
use PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType\CommentHasAlreadyBeenTrashedErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractCommentHasAlreadyBeenTrashedErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?CommentHasAlreadyBeenTrashedErrorPayloadObjectTypeResolver $commentHasAlreadyBeenTrashedErrorPayloadObjectTypeResolver = null;

    final protected function getCommentHasAlreadyBeenTrashedErrorPayloadObjectTypeResolver(): CommentHasAlreadyBeenTrashedErrorPayloadObjectTypeResolver
    {
        if ($this->commentHasAlreadyBeenTrashedErrorPayloadObjectTypeResolver === null) {
            /** @var CommentHasAlreadyBeenTrashedErrorPayloadObjectTypeResolver */
            $commentHasAlreadyBeenTrashedErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(CommentHasAlreadyBeenTrashedErrorPayloadObjectTypeResolver::class);
            $this->commentHasAlreadyBeenTrashedErrorPayloadObjectTypeResolver = $commentHasAlreadyBeenTrashedErrorPayloadObjectTypeResolver;
        }
        return $this->commentHasAlreadyBeenTrashedErrorPayloadObjectTypeResolver;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getCommentHasAlreadyBeenTrashedErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return CommentHasAlreadyBeenTrashedErrorPayload::class;
    }
}
