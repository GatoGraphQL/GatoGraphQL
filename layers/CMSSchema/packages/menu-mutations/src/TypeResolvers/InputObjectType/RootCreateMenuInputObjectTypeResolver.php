<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\TypeResolvers\InputObjectType;

class RootCreateMenuInputObjectTypeResolver extends AbstractCreateMenuInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootCreateMenuInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to create a menu', 'menu-mutations');
    }
}
