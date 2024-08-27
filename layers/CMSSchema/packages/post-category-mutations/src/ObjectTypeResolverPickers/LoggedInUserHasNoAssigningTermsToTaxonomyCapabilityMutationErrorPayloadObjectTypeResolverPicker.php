<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\PostCategoryMutations\TypeResolvers\UnionType\AbstractPostCategoriesMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostMutations\TypeResolvers\UnionType\PostUpdateMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostMutations\TypeResolvers\UnionType\RootCreatePostMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostMutations\TypeResolvers\UnionType\RootUpdatePostMutationErrorPayloadUnionTypeResolver;
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
            AbstractPostCategoriesMutationErrorPayloadUnionTypeResolver::class,
            PostUpdateMutationErrorPayloadUnionTypeResolver::class,
            RootCreatePostMutationErrorPayloadUnionTypeResolver::class,
            RootUpdatePostMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
