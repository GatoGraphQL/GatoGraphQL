<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CustomPostCategoryMutations\TypeResolvers\UnionType\AbstractGenericCategoriesMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostMutations\TypeResolvers\UnionType\GenericCustomPostUpdateMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostMutations\TypeResolvers\UnionType\RootCreateGenericCustomPostMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostMutations\TypeResolvers\UnionType\RootUpdateGenericCustomPostMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\TaxonomyMutations\ObjectTypeResolverPickers\AbstractLoggedInUserHasNoAssigningTermsToTaxonomyCapabilityErrorPayloadObjectTypeResolverPicker;
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
            GenericCustomPostUpdateMutationErrorPayloadUnionTypeResolver::class,
            RootCreateGenericCustomPostMutationErrorPayloadUnionTypeResolver::class,
            RootUpdateGenericCustomPostMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
