<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\InterfaceType;

class IdentifiableObjectInterfaceTypeResolver extends AbstractInterfaceTypeResolver
{
    public function getTypeName(): string
    {
        return 'IdentifiableObject';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('An object that can be uniquely identifiable within its type via an \'ID\', and within the whole schema via a \'global ID\'', 'component-model');
    }
}
