<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\Object;

use PoP\ComponentModel\TypeResolvers\AbstractRelationalTypeResolver;

abstract class AbstractObjectTypeResolver extends AbstractRelationalTypeResolver implements ObjectTypeResolverInterface
{
    public function getSelfFieldTypeResolverClass(): string
    {
        return get_called_class();
    }
}
