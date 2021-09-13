<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\ComponentModel\TypeResolvers\AbstractRelationalTypeResolver;

trait GlobalRelationalTypeDirectiveResolverTrait
{
    use GlobalDirectiveResolverTrait;

    public function getRelationalTypeOrInterfaceTypeResolverClassesToAttachTo(): array
    {
        // Global: Be attached to all RelationalTypeResolvers
        return [
            AbstractRelationalTypeResolver::class,
        ];
    }
}
