<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutations\TypeResolvers\ObjectType;

class RootUpdateUserMutationPayloadObjectTypeResolver extends AbstractUserMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootUpdateUserMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of updating a user', 'gatographql');
    }
}
