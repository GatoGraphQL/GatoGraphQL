<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateMutations\TypeResolvers\ObjectType;

class RootLoginUserMutationPayloadObjectTypeResolver extends AbstractUserStateMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootLoginUserMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of logging the user in', 'user-state-mutations');
    }
}
