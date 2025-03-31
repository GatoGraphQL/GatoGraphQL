<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\ObjectType;

class RootDeleteGenericCustomPostMetaMutationPayloadObjectTypeResolver extends AbstractGenericCustomPostMetaMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootDeleteGenericCustomPostMetaMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of executing a delete meta mutation on a custom post', 'custompost-mutations');
    }
}
