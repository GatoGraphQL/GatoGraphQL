<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\TypeResolvers\ObjectType;

class CommentDeleteMetaMutationPayloadObjectTypeResolver extends AbstractCommentMetaMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'CommentDeleteMetaMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of executing a delete meta nested mutation on a comment', 'comment-mutations');
    }
}
