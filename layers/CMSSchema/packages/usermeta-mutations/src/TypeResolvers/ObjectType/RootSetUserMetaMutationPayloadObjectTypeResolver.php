<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\TypeResolvers\ObjectType;

class RootSetUserMetaMutationPayloadObjectTypeResolver extends AbstractUserMetaMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootSetUserMetaMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of executing a set meta mutation on a user', 'user-mutations');
    }
}
