<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMutations\TypeResolvers\ObjectType;

class PostNestedUpdateMutationPayloadObjectTypeResolver extends AbstractPostMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'PostNestedUpdateMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of executing an update nested mutation on a post', 'post-mutations');
    }
}
