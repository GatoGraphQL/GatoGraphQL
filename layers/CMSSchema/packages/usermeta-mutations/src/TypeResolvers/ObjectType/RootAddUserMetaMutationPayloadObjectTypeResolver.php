<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\TypeResolvers\ObjectType;

class RootAddUserMetaMutationPayloadObjectTypeResolver extends AbstractUserMetaMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootAddUserMetaMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of adding meta to a user', 'user-mutations');
    }
}
