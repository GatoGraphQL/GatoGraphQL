<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\TypeResolvers\ObjectType;

class GenericUserUpdateMetaMutationPayloadObjectTypeResolver extends AbstractGenericUserMetaMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'GenericUserUpdateMetaMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of executing an update meta nested mutation on a user', 'user-mutations');
    }
}
