<?php

declare(strict_types=1);

namespace PoPSchema\Meta\TypeResolvers\InterfaceType;

use PoP\ComponentModel\TypeResolvers\InterfaceType\AbstractInterfaceTypeResolver;

class WithMetaInterfaceTypeResolver extends AbstractInterfaceTypeResolver
{
    public function getTypeName(): string
    {
        return 'WithMeta';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Fields with meta values', 'custompostmeta');
    }
}
