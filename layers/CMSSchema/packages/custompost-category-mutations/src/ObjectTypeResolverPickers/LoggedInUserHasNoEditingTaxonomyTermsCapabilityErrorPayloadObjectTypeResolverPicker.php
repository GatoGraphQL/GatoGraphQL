<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CustomPostCategoryMutations\TypeResolvers\UnionType\GenericCategoryDeleteMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostCategoryMutations\TypeResolvers\UnionType\GenericCategoryUpdateMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostCategoryMutations\TypeResolvers\UnionType\RootCreateGenericCategoryTermMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostCategoryMutations\TypeResolvers\UnionType\RootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostCategoryMutations\TypeResolvers\UnionType\RootUpdateGenericCategoryTermMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\TaxonomyMutations\ObjectTypeResolverPickers\AbstractLoggedInUserHasNoEditingTaxonomyTermsCapabilityErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class LoggedInUserHasNoEditingTaxonomyTermsCapabilityErrorPayloadObjectTypeResolverPicker extends AbstractLoggedInUserHasNoEditingTaxonomyTermsCapabilityErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            RootCreateGenericCategoryTermMutationErrorPayloadUnionTypeResolver::class,
            RootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeResolver::class,
            RootUpdateGenericCategoryTermMutationErrorPayloadUnionTypeResolver::class,
            GenericCategoryDeleteMutationErrorPayloadUnionTypeResolver::class,
            GenericCategoryUpdateMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
