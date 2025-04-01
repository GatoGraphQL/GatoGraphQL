<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\TypeResolvers\ObjectType;

class GenericUserDeleteMetaMutationPayloadObjectTypeResolver extends AbstractGenericUserMetaMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'GenericUserDeleteMetaMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of executing a delete meta nested mutation on a user', 'user-mutations');
    }
}
