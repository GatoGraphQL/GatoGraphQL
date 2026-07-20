<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType;

class CommentDeleteMutationPayloadObjectTypeResolver extends AbstractCommentMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'CommentDeleteMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of deleting a comment (nested mutations)', 'gatographql');
    }
}
