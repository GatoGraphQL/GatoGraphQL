<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\UnionType\GenericCategoryDeleteMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\UnionType\RootDeleteGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\TaxonomyMetaMutations\ObjectTypeResolverPickers\AbstractLoggedInUserHasNoDeletingTaxonomyTermCapabilityErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class LoggedInUserHasNoDeletingTaxonomyTermCapabilityErrorPayloadObjectTypeResolverPicker extends AbstractLoggedInUserHasNoDeletingTaxonomyTermCapabilityErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            RootDeleteGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver::class,
            GenericCategoryDeleteMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
