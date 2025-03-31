<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMetaMutations\TypeResolvers\ObjectType;

class RootAddPostMetaMutationPayloadObjectTypeResolver extends AbstractPostMetaMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootAddPostMetaMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of adding meta to a posts\'s custom post', 'custompost-mutations');
    }
}
