<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMutations\TypeResolvers\ObjectType;

class RootCreatePostMutationPayloadObjectTypeResolver extends AbstractPostMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootCreatePostMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of creating a post', 'post-mutations');
    }
}
