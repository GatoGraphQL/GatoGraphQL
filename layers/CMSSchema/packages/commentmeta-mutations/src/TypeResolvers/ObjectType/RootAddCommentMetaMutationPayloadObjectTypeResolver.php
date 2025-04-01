<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\TypeResolvers\ObjectType;

class RootAddCommentMetaMutationPayloadObjectTypeResolver extends AbstractCommentMetaMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootAddCommentMetaMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of adding meta to a comment', 'comment-mutations');
    }
}
