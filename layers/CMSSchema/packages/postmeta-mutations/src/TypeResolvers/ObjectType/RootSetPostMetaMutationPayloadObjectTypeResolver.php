<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMetaMutations\TypeResolvers\ObjectType;

class RootSetPostMetaMutationPayloadObjectTypeResolver extends AbstractPostMetaMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootSetPostMetaMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of executing a set meta mutation on a post', 'custompost-mutations');
    }
}
