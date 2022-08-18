<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\ComponentModel\TypeResolvers\AbstractRelationalTypeResolver;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

trait GlobalDirectiveResolverTrait
{
    public function isGlobal(RelationalTypeResolverInterface $relationalTypeResolver): bool
    {
        return true;
    }

    /**
     * @return class-string<InterfaceTypeResolverInterface|RelationalTypeResolverInterface>
     */
    public function getRelationalTypeOrInterfaceTypeResolverClassesToAttachTo(): array
    {
        // Global: Be attached to all RelationalTypeResolvers
        return [
            AbstractRelationalTypeResolver::class,
        ];
    }
}
