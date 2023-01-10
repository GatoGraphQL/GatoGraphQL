<?php

declare(strict_types=1);

namespace PoPCMSSchema\Tags\TypeResolvers\InterfaceType;

use PoP\ComponentModel\TypeResolvers\InterfaceType\AbstractInterfaceTypeResolver;

class TagInterfaceTypeResolver extends AbstractInterfaceTypeResolver
{
    public function getTypeName(): string
    {
        return 'Tag';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Entities representing a tag', 'tags');
    }
}
