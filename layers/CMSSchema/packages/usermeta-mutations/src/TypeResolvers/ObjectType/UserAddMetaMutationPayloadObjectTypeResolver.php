<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\TypeResolvers\ObjectType;

class UserAddMetaMutationPayloadObjectTypeResolver extends AbstractUserMetaMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'UserAddMetaMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of executing an add meta nested mutation on a user', 'user-mutations');
    }
}
