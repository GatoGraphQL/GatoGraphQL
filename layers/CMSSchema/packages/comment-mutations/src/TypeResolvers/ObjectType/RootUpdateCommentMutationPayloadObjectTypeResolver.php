<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType;

class RootUpdateCommentMutationPayloadObjectTypeResolver extends AbstractCommentMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootUpdateCommentMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of updating a comment', 'gatographql');
    }
}
