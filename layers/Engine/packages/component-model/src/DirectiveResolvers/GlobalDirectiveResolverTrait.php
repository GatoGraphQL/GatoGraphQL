<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\ComponentModel\TypeResolvers\Object\AbstractObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\Object\ObjectTypeResolverInterface;

trait GlobalDirectiveResolverTrait
{
    public function getObjectTypeOrInterfaceTypeResolverClassesToAttachTo(): array
    {
        // Global: Be attached to all ObjectTypeResolvers
        return [
            AbstractObjectTypeResolver::class,
        ];
    }

    public function isGlobal(ObjectTypeResolverInterface $objectTypeResolver): bool
    {
        return true;
    }
}
