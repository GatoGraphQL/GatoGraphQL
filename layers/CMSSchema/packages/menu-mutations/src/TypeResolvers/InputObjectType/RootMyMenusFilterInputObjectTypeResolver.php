<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\Menus\TypeResolvers\InputObjectType\AbstractMenusFilterInputObjectTypeResolver;

class RootMyMenusFilterInputObjectTypeResolver extends AbstractMenusFilterInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootMyMenusFilterInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to filter the logged-in user\'s menus', 'menu-mutations');
    }
}
