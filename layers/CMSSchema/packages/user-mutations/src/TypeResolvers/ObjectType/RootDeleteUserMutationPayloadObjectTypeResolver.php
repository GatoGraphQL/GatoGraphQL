<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutations\TypeResolvers\ObjectType;

class RootDeleteUserMutationPayloadObjectTypeResolver extends AbstractUserMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootDeleteUserMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of deleting a user', 'gatographql');
    }
}
