<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMetaMutations\TypeResolvers\ObjectType;

class RootSetPostTagTermMetaMutationPayloadObjectTypeResolver extends AbstractPostTagMetaMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootSetPostTagTermMetaMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of executing a set meta mutation on a post\'s tag term', 'tag-mutations');
    }
}
