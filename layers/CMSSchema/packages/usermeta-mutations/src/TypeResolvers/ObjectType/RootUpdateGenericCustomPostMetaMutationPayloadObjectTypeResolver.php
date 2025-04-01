<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\TypeResolvers\ObjectType;

class RootUpdateGenericUserMetaMutationPayloadObjectTypeResolver extends AbstractGenericUserMetaMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootUpdateGenericUserMetaMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of executing an update meta mutation on a user', 'user-mutations');
    }
}
