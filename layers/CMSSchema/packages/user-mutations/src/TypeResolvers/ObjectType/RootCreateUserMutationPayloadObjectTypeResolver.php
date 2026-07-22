<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutations\TypeResolvers\ObjectType;

class RootCreateUserMutationPayloadObjectTypeResolver extends AbstractUserMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootCreateUserMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of creating a user', 'gatographql');
    }
}
