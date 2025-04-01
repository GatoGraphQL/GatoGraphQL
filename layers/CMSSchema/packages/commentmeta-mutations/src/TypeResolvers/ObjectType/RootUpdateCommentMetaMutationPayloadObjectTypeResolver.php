<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\TypeResolvers\ObjectType;

class RootUpdateCommentMetaMutationPayloadObjectTypeResolver extends AbstractCommentMetaMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootUpdateCommentMetaMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of executing an update meta mutation on a comment', 'comment-mutations');
    }
}
