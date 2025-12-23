<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\TypeResolvers\ObjectType;

class RootUpdateMenuMutationPayloadObjectTypeResolver extends AbstractMenuMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootUpdateMenuMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of updating a menu', 'menu-mutations');
    }
}
