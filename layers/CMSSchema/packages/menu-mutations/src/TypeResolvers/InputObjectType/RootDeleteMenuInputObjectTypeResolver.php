<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\TypeResolvers\InputObjectType;

class RootDeleteMenuInputObjectTypeResolver extends AbstractDeleteMenuInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootDeleteMenuInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to delete a menu', 'gatographql');
    }
}
