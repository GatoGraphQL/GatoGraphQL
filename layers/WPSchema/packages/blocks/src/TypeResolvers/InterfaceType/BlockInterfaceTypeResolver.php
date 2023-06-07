<?php

declare(strict_types=1);

namespace PoPWPSchema\Blocks\TypeResolvers\InterfaceType;

use PoP\ComponentModel\TypeResolvers\InterfaceType\AbstractInterfaceTypeResolver;

class BlockInterfaceTypeResolver extends AbstractInterfaceTypeResolver
{
    public function getTypeName(): string
    {
        return 'Block';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Entities representing a Block', 'blocks');
    }
}
