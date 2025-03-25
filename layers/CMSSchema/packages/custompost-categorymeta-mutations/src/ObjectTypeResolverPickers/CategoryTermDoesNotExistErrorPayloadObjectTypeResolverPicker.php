<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CategoryMutations\ObjectTypeResolverPickers\AbstractCategoryTermDoesNotExistErrorPayloadObjectTypeResolverPicker;
use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\UnionType\AbstractGenericCategoriesMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\UnionType\GenericCategoryDeleteMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\UnionType\GenericCategoryUpdateMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\UnionType\RootCreateGenericCategoryTermMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\UnionType\RootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\UnionType\RootUpdateGenericCategoryTermMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CategoryMutations\TypeResolvers\UnionType\GenericCategoryUpdateMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CategoryMutations\TypeResolvers\UnionType\RootCreateGenericCategoryMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CategoryMutations\TypeResolvers\UnionType\RootUpdateGenericCategoryMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class CategoryTermDoesNotExistErrorPayloadObjectTypeResolverPicker extends AbstractCategoryTermDoesNotExistErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractGenericCategoriesMutationErrorPayloadUnionTypeResolver::class,
            RootCreateGenericCategoryTermMutationErrorPayloadUnionTypeResolver::class,
            RootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeResolver::class,
            RootUpdateGenericCategoryTermMutationErrorPayloadUnionTypeResolver::class,
            GenericCategoryDeleteMutationErrorPayloadUnionTypeResolver::class,
            GenericCategoryUpdateMutationErrorPayloadUnionTypeResolver::class,
            GenericCategoryUpdateMutationErrorPayloadUnionTypeResolver::class,
            RootCreateGenericCategoryMutationErrorPayloadUnionTypeResolver::class,
            RootUpdateGenericCategoryMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
