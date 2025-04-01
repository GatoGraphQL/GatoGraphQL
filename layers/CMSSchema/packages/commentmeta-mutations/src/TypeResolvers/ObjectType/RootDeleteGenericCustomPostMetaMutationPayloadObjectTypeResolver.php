<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\TypeResolvers\ObjectType;

class RootDeleteGenericCommentMetaMutationPayloadObjectTypeResolver extends AbstractGenericCommentMetaMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootDeleteGenericCommentMetaMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of executing a delete meta mutation on a comment', 'comment-mutations');
    }
}
