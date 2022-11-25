<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType;

class CommentCreateMutationPayloadObjectTypeResolver extends AbstractCommentMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'CommentCreateMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of adding a comment', 'comment-mutations');
    }
}
