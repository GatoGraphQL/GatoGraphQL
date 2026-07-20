<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\TypeResolvers\ObjectType;

class RootDeleteMenuMutationPayloadObjectTypeResolver extends AbstractMenuMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootDeleteMenuMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of deleting a menu', 'gatographql');
    }
}
