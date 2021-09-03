<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers;

use PoP\ComponentModel\TypeResolvers\AbstractObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;

trait GlobalFieldResolverTrait
{
    public function getClassesToAttachTo(): array
    {
        return [
            AbstractObjectTypeResolver::class,
        ];
    }

    public function isGlobal(TypeResolverInterface $typeResolver, string $fieldName): bool
    {
        return true;
    }
}
