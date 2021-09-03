<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers;

use PoP\ComponentModel\TypeResolvers\AbstractRelationalTypeResolver;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

trait GlobalFieldResolverTrait
{
    public function getClassesToAttachTo(): array
    {
        return [
            AbstractRelationalTypeResolver::class,
        ];
    }

    public function isGlobal(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): bool
    {
        return true;
    }
}
