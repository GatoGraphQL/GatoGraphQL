<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\TypeResolvers\ObjectType;

class UserSetMetaMutationPayloadObjectTypeResolver extends AbstractUserMetaMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'UserSetMetaMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of executing a set meta nested mutation on a user', 'user-mutations');
    }
}
