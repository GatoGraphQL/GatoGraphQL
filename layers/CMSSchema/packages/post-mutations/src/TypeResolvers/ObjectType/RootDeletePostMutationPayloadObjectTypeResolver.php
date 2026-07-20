<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMutations\TypeResolvers\ObjectType;

class RootDeletePostMutationPayloadObjectTypeResolver extends AbstractPostMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootDeletePostMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of executing a delete mutation on a post', 'gatographql');
    }
}
