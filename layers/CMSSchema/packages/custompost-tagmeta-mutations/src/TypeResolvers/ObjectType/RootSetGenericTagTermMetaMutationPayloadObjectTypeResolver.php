<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMetaMutations\TypeResolvers\ObjectType;

class RootSetGenericTagTermMetaMutationPayloadObjectTypeResolver extends AbstractGenericTagMetaMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootSetGenericTagTermMetaMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of executing a set meta mutation on a tag term', 'tag-mutations');
    }
}
