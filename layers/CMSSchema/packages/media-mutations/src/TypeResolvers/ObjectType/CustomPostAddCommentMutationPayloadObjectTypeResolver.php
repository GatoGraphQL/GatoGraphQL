<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\TypeResolvers\ObjectType;

class CustomPostAddCommentMutationPayloadObjectTypeResolver extends AbstractCommentMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'CustomPostAddCommentMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of adding a comment to a custom post (using nested mutations)', 'media-mutations');
    }
}
