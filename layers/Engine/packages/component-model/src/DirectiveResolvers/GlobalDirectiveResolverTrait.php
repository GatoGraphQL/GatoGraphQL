<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\ComponentModel\TypeResolvers\AbstractRelationalTypeResolver;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

trait GlobalDirectiveResolverTrait
{
    public function getClassesToAttachTo(): array
    {
        // Be attached to all typeResolvers
        return [
            AbstractRelationalTypeResolver::class,
        ];
    }

    public function isGlobal(RelationalTypeResolverInterface $relationalTypeResolver): bool
    {
        return true;
    }
}
