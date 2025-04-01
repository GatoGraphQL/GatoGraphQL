<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\TypeResolvers\ObjectType;

class GenericCommentSetMetaMutationPayloadObjectTypeResolver extends AbstractGenericCommentMetaMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'GenericCommentSetMetaMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of executing a set meta nested mutation on a comment', 'comment-mutations');
    }
}
