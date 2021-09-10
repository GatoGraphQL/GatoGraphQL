<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\Interface;

use PoP\ComponentModel\TypeResolvers\Interface\AbstractInterfaceTypeResolver;

class ElementalInterfaceTypeResolver extends AbstractInterfaceTypeResolver
{
    public function getTypeName(): string
    {
        return 'Elemental';
    }

    public function getSchemaTypeDescription(): ?string
    {
        return $this->translationAPI->__('The fundamental fields that must be implemented by all objects', 'component-model');
    }
}
