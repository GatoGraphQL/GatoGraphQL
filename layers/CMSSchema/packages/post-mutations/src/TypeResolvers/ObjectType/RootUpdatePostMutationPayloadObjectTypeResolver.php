<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMutations\TypeResolvers\ObjectType;

class RootUpdatePostMutationPayloadObjectTypeResolver extends AbstractPostMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootUpdatePostMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of executing an update mutation on a post', 'post-mutations');
    }
}
