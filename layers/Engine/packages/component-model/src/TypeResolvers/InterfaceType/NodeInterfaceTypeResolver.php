<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\InterfaceType;

class NodeInterfaceTypeResolver extends AbstractInterfaceTypeResolver
{
    public function getTypeName(): string
    {
        return 'Node';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('The fundamental fields that must be implemented by all objects', 'component-model');
    }
}
