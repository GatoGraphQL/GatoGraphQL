<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategories\ObjectTypeResolverPickers;

use PoPCMSSchema\Categories\TypeResolvers\UnionType\CategoryUnionTypeResolver;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class CategoryUnionPostCategoryObjectTypeResolverPicker extends AbstractPostCategoryObjectTypeResolverPicker
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
