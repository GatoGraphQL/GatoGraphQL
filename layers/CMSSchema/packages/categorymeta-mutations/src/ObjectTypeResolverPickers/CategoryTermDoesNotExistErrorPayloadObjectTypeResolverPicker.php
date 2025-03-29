<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMetaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CategoryMutations\ObjectTypeResolverPickers\AbstractCategoryTermDoesNotExistErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
use PoPCMSSchema\CategoryMetaMutations\TypeResolvers\UnionType\AbstractRootCategoryTermMetaMutationErrorPayloadUnionTypeResolver;

class CategoryTermDoesNotExistErrorPayloadObjectTypeResolverPicker extends AbstractCategoryTermDoesNotExistErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractRootCategoryTermMetaMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
