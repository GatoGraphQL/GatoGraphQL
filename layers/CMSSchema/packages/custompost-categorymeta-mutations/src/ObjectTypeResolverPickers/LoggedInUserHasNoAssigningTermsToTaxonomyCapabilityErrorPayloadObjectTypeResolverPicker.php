<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\UnionType\AbstractGenericCategoriesMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CategoryMutations\TypeResolvers\UnionType\GenericCategoryUpdateMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CategoryMutations\TypeResolvers\UnionType\RootCreateGenericCategoryMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CategoryMutations\TypeResolvers\UnionType\RootUpdateGenericCategoryMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\TaxonomyMetaMutations\ObjectTypeResolverPickers\AbstractLoggedInUserHasNoAssigningTermsToTaxonomyCapabilityErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class LoggedInUserHasNoAssigningTermsToTaxonomyCapabilityErrorPayloadObjectTypeResolverPicker extends AbstractLoggedInUserHasNoAssigningTermsToTaxonomyCapabilityErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractGenericCategoriesMutationErrorPayloadUnionTypeResolver::class,
            GenericCategoryUpdateMutationErrorPayloadUnionTypeResolver::class,
            RootCreateGenericCategoryMutationErrorPayloadUnionTypeResolver::class,
            RootUpdateGenericCategoryMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
