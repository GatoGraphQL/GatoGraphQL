<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CategoryMutations\ObjectTypeResolverPickers\AbstractCategoryTermDoesNotExistErrorPayloadObjectTypeResolverPicker;
use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\UnionType\AbstractGenericCategoriesMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\UnionType\GenericCategoryDeleteMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\UnionType\GenericCategoryUpdateMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\UnionType\RootCreateGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\UnionType\RootDeleteGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\UnionType\RootUpdateGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver;
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
            RootCreateGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver::class,
            RootDeleteGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver::class,
            RootUpdateGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver::class,
            GenericCategoryDeleteMutationErrorPayloadUnionTypeResolver::class,
            GenericCategoryUpdateMutationErrorPayloadUnionTypeResolver::class,
            GenericCategoryUpdateMutationErrorPayloadUnionTypeResolver::class,
            RootCreateGenericCategoryMutationErrorPayloadUnionTypeResolver::class,
            RootUpdateGenericCategoryMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
