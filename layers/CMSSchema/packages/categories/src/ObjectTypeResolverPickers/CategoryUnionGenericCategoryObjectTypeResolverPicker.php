<?php

declare(strict_types=1);

namespace PoPCMSSchema\Categories\ObjectTypeResolverPickers;

use PoPCMSSchema\Categories\TypeResolvers\UnionType\CategoryUnionTypeResolver;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class CategoryUnionGenericCategoryObjectTypeResolverPicker extends AbstractGenericCategoryObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            CategoryUnionTypeResolver::class,
        ];
    }
}
