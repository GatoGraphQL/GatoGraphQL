<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CommentMutations\ObjectModels\CommentDoesNotSupportTrashErrorPayload;
use PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType\CommentDoesNotSupportTrashErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractCommentDoesNotSupportTrashErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?CommentDoesNotSupportTrashErrorPayloadObjectTypeResolver $commentDoesNotSupportTrashErrorPayloadObjectTypeResolver = null;

    final protected function getCommentDoesNotSupportTrashErrorPayloadObjectTypeResolver(): CommentDoesNotSupportTrashErrorPayloadObjectTypeResolver
    {
        if ($this->commentDoesNotSupportTrashErrorPayloadObjectTypeResolver === null) {
            /** @var CommentDoesNotSupportTrashErrorPayloadObjectTypeResolver */
            $commentDoesNotSupportTrashErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(CommentDoesNotSupportTrashErrorPayloadObjectTypeResolver::class);
            $this->commentDoesNotSupportTrashErrorPayloadObjectTypeResolver = $commentDoesNotSupportTrashErrorPayloadObjectTypeResolver;
        }
        return $this->commentDoesNotSupportTrashErrorPayloadObjectTypeResolver;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getCommentDoesNotSupportTrashErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return CommentDoesNotSupportTrashErrorPayload::class;
    }
}
