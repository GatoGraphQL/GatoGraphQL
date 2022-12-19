<?php

declare(strict_types=1);

namespace PoPCMSSchema\Tags\TypeResolvers\InterfaceType;

use PoP\ComponentModel\TypeResolvers\InterfaceType\AbstractInterfaceTypeResolver;

class IsTagInterfaceTypeResolver extends AbstractInterfaceTypeResolver
{
    public function getTypeName(): string
    {
        return 'IsTag';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Entities representing a tag', 'tags');
    }
}
