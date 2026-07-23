<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutations\TypeResolvers\ObjectType;

class UserDeleteMutationPayloadObjectTypeResolver extends AbstractUserMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'UserDeleteMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of deleting a user (nested mutations)', 'gatographql');
    }
}
