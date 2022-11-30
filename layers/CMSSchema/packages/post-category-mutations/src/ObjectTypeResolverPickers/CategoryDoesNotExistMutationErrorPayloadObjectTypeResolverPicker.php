<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CustomPostCategoryMutations\ObjectTypeResolverPickers\AbstractCategoryDoesNotExistErrorPayloadObjectTypeResolverPicker;
use PoPCMSSchema\PostCategoryMutations\TypeResolvers\UnionType\AbstractSetCategoriesOnPostMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class CategoryDoesNotExistMutationErrorPayloadObjectTypeResolverPicker extends AbstractCategoryDoesNotExistErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractSetCategoriesOnPostMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
