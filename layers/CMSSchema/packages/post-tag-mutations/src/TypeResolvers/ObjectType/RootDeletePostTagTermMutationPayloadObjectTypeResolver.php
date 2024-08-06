<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMutations\TypeResolvers\ObjectType;

class RootDeletePostTagTermMutationPayloadObjectTypeResolver extends AbstractPostTagMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootDeletePostTagTermMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of executing a delete mutation on a post tag term', 'tag-mutations');
    }
}
