<?php

declare(strict_types=1);

namespace PoPCMSSchema\MetaMutations\TypeResolvers\InterfaceType;

use PoP\ComponentModel\TypeResolvers\InterfaceType\AbstractInterfaceTypeResolver;

class WithMetaMutationsInterfaceTypeResolver extends AbstractInterfaceTypeResolver
{
    public function getTypeName(): string
    {
        return 'WithMetaMutations';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Object types with meta mutations', 'meta-mutations');
    }
}
