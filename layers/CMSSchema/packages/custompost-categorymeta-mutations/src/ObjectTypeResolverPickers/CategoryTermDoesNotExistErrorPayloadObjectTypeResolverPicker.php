<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CategoryMutations\ObjectTypeResolverPickers\AbstractCategoryTermDoesNotExistErrorPayloadObjectTypeResolverPicker;
use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\UnionType\AbstractGenericCategoriesMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\UnionType\GenericCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\UnionType\GenericCategoryUpdateMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\UnionType\RootAddGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\UnionType\RootDeleteGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\UnionType\RootUpdateGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CategoryMutations\TypeResolvers\UnionType\GenericCategoryUpdateMetaMutationErrorPayloadUnionTypeResolver;
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
            RootAddGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver::class,
            RootDeleteGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver::class,
            RootUpdateGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver::class,
            GenericCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver::class,
            GenericCategoryUpdateMetaMutationErrorPayloadUnionTypeResolver::class,
            GenericCategoryUpdateMetaMutationErrorPayloadUnionTypeResolver::class,
            RootCreateGenericCategoryMutationErrorPayloadUnionTypeResolver::class,
            RootUpdateGenericCategoryMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
