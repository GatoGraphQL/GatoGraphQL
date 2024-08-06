<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\ObjectTypeResolverPickers;

use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class CategoryDoesNotExistMutationErrorPayloadObjectTypeResolverPicker extends AbstractCategoryDoesNotExistErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            /**
             * Commented because the input is an Enum type, hence
             * the taxonomy will always exist
             */
            // AbstractRootCreateCategoryMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
