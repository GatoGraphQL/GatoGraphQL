<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CategoryMutations\TypeResolvers\UnionType\AbstractRootUpdateCategoryMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class CategoryDoesNotExistMutationErrorPayloadObjectTypeResolverPicker extends AbstractCategoryDoesNotExistErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractRootUpdateCategoryMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
