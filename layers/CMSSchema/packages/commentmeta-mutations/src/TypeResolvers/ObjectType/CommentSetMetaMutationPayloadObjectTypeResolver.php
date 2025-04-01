<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\TypeResolvers\ObjectType;

class CommentSetMetaMutationPayloadObjectTypeResolver extends AbstractCommentMetaMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'CommentSetMetaMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of executing a set meta nested mutation on a comment', 'comment-mutations');
    }
}
