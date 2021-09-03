<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\ComponentModel\TypeResolvers\AbstractObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ObjectTypeResolverInterface;

trait GlobalDirectiveResolverTrait
{
    public function getClassesToAttachTo(): array
    {
        // Be attached to all typeResolvers
        return [
            AbstractObjectTypeResolver::class,
        ];
    }

    public function isGlobal(ObjectTypeResolverInterface $typeResolver): bool
    {
        return true;
    }
}
