<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers;

use PoP\ComponentModel\TypeResolvers\Object\AbstractObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\Object\ObjectTypeResolverInterface;

trait GlobalFieldResolverTrait
{
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractObjectTypeResolver::class,
        ];
    }

    public function isGlobal(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): bool
    {
        return true;
    }
}
