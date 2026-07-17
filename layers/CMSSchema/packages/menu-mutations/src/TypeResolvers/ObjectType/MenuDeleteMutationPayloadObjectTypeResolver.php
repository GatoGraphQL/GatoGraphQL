<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\TypeResolvers\ObjectType;

class MenuDeleteMutationPayloadObjectTypeResolver extends AbstractMenuMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'MenuDeleteMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of deleting a menu (nested mutations)', 'gatographql');
    }
}
