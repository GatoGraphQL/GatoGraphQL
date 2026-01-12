<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\TypeResolvers\ObjectType;

class MenuUpdateMutationPayloadObjectTypeResolver extends AbstractMenuMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'MenuUpdateMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of updating a menu (nested mutations)', 'menu-mutations');
    }
}
