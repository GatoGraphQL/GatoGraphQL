<?php

declare(strict_types=1);

namespace PoPCMSSchema\Menus\TypeResolvers\InputObjectType;

class RootMenusFilterInputObjectTypeResolver extends AbstractMenusFilterInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootMenusFilterInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to filter menus', 'menus');
    }
}
