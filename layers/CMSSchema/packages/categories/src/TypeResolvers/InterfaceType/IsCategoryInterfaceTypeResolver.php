<?php

declare(strict_types=1);

namespace PoPCMSSchema\Categories\TypeResolvers\InterfaceType;

use PoP\ComponentModel\TypeResolvers\InterfaceType\AbstractInterfaceTypeResolver;

class CategoryInterfaceTypeResolver extends AbstractInterfaceTypeResolver
{
    public function getTypeName(): string
    {
        return 'Category';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Entities representing a category', 'categories');
    }
}
