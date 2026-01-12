<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\TypeResolvers\InputObjectType;

class RootUpdateMenuInputObjectTypeResolver extends AbstractUpdateMenuInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootUpdateMenuInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to update a menu', 'menu-mutations');
    }
}
