<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMutations\TypeResolvers\ObjectType;

class PostTagDeleteMutationPayloadObjectTypeResolver extends AbstractPostTagMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'PostTagDeleteMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of executing a delete nested mutation on a post tag', 'tag-mutations');
    }
}
