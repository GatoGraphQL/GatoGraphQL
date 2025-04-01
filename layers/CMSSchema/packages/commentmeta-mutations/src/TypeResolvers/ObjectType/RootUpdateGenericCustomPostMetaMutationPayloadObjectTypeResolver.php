<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\TypeResolvers\ObjectType;

class RootUpdateGenericCommentMetaMutationPayloadObjectTypeResolver extends AbstractGenericCommentMetaMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootUpdateGenericCommentMetaMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of executing an update meta mutation on a comment', 'comment-mutations');
    }
}
