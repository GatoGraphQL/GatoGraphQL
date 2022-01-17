<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\TypeResolvers\InterfaceType;

use PoP\ComponentModel\TypeResolvers\InterfaceType\AbstractInterfaceTypeResolver;

class IsCustomPostInterfaceTypeResolver extends AbstractInterfaceTypeResolver
{
    public function getTypeName(): string
    {
        return 'IsCustomPost';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Entities representing a custom post', 'customposts');
    }
}
