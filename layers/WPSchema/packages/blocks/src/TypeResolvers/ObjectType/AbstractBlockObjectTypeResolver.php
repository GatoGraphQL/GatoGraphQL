<?php

declare(strict_types=1);

namespace PoPWPSchema\Blocks\TypeResolvers\ObjectType;

use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractTransientObjectObjectTypeResolver;

abstract class AbstractBlockObjectTypeResolver extends AbstractTransientObjectObjectTypeResolver
{
    public function getTypeDescription(): ?string
    {
        return $this->__('Block', 'blocks');
    }
}
