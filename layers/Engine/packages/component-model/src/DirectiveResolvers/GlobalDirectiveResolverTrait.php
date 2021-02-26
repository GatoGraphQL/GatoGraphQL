<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;

trait GlobalDirectiveResolverTrait
{
    public function getClassesToAttachTo(): array
    {
        // Be attached to all typeResolvers
        return [
            AbstractTypeResolver::class,
        ];
    }

    public function isGlobal(TypeResolverInterface $typeResolver): bool
    {
        return true;
    }
}
