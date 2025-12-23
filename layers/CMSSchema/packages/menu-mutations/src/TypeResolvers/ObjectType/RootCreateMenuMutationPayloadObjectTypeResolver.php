<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\TypeResolvers\ObjectType;

class RootCreateMenuMutationPayloadObjectTypeResolver extends AbstractMenuMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootCreateMenuMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of creating a menu', 'menu-mutations');
    }
}
