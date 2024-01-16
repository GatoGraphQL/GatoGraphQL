<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\TypeResolvers\ObjectType;

class CommentReplyMutationPayloadObjectTypeResolver extends AbstractCommentMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'CommentReplyMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of replying to a comment (using nested mutations)', 'media-mutations');
    }
}
