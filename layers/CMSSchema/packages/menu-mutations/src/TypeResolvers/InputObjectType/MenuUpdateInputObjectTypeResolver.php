<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\TypeResolvers\InputObjectType;

class MenuUpdateInputObjectTypeResolver extends AbstractUpdateMenuInputObjectTypeResolver
{
    protected function addMenuInputField(): bool
    {
        return false;
    }

    public function getTypeName(): string
    {
        return 'MenuUpdateInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to update a menu (nested mutations)', 'menu-mutations');
    }
}
