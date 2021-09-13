<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;

trait GlobalObjectTypeDirectiveResolverTrait
{
    use GlobalDirectiveResolverTrait;

    public function getRelationalTypeOrInterfaceTypeResolverClassesToAttachTo(): array
    {
        // Global: Be attached to all ObjectTypeResolvers
        return [
            AbstractObjectTypeResolver::class,
        ];
    }
}
