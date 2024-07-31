<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CategoryMutations\TypeResolvers\UnionType\AbstractCategoryDeleteMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CategoryMutations\TypeResolvers\UnionType\AbstractRootDeleteCategoryMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\TaxonomyMutations\ObjectTypeResolverPickers\AbstractLoggedInUserHasNoEditingTaxonomyTermsCapabilityErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class LoggedInUserHasNoDeletingTaxonomyTermCapabilityMutationErrorPayloadObjectTypeResolverPicker extends AbstractLoggedInUserHasNoEditingTaxonomyTermsCapabilityErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractRootDeleteCategoryMutationErrorPayloadUnionTypeResolver::class,
            AbstractCategoryDeleteMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
