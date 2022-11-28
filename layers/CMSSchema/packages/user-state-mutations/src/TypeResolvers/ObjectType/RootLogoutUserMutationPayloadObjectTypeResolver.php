<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateMutations\TypeResolvers\ObjectType;

class RootLogoutUserMutationPayloadObjectTypeResolver extends AbstractUserStateMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootLogoutUserMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of logging the user out', 'user-state-mutations');
    }
}
