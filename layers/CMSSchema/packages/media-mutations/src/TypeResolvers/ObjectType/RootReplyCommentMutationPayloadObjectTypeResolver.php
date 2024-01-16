<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\TypeResolvers\ObjectType;

class RootReplyCommentMutationPayloadObjectTypeResolver extends AbstractCommentMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootReplyCommentMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of replying to a comment', 'media-mutations');
    }
}
