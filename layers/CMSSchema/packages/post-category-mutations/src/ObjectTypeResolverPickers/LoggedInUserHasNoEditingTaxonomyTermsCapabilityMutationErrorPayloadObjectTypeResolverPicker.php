<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CategoryMutations\TypeResolvers\UnionType\AbstractCategoryUpdateMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\TaxonomyMutations\ObjectTypeResolverPickers\AbstractLoggedInUserHasNoEditingTaxonomyTermsCapabilityErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
use PoPCMSSchema\CategoryMutations\TypeResolvers\UnionType\AbstractRootCreateCategoryMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CategoryMutations\TypeResolvers\UnionType\AbstractRootUpdateCategoryMutationErrorPayloadUnionTypeResolver;

class LoggedInUserHasNoEditingTaxonomyTermsCapabilityMutationErrorPayloadObjectTypeResolverPicker extends AbstractLoggedInUserHasNoEditingTaxonomyTermsCapabilityErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractCategoryUpdateMutationErrorPayloadUnionTypeResolver::class,
            AbstractRootCreateCategoryMutationErrorPayloadUnionTypeResolver::class,
            AbstractRootUpdateCategoryMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
