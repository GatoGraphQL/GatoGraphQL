<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutations\TypeResolvers\ObjectType;

class UserUpdateMutationPayloadObjectTypeResolver extends AbstractUserMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'UserUpdateMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of updating a user (nested mutations)', 'gatographql');
    }
}
