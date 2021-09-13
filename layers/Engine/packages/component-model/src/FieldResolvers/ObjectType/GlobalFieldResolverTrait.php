<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers\ObjectType;

use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

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
