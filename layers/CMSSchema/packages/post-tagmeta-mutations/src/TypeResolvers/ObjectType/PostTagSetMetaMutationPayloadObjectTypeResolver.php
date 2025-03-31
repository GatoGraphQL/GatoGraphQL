<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMetaMutations\TypeResolvers\ObjectType;

class PostTagSetMetaMutationPayloadObjectTypeResolver extends AbstractPostTagMetaMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'PostTagSetMetaMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of executing a set meta nested mutation on a post\'s tag term', 'tag-mutations');
    }
}
