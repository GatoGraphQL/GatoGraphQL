<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\TypeResolvers\InterfaceType;

use PoP\ComponentModel\TypeResolvers\InterfaceType\AbstractInterfaceTypeResolver;

class CustomPostInterfaceTypeResolver extends AbstractInterfaceTypeResolver
{
    public function getTypeName(): string
    {
        return 'CustomPost';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Entities representing a custom post', 'customposts');
    }
}
