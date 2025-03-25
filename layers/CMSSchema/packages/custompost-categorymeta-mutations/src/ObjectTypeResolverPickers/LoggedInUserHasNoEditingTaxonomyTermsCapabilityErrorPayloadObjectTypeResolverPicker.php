<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\UnionType\GenericCategoryDeleteMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\UnionType\GenericCategoryUpdateMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\UnionType\RootCreateGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\UnionType\RootDeleteGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\UnionType\RootUpdateGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\TaxonomyMetaMutations\ObjectTypeResolverPickers\AbstractLoggedInUserHasNoEditingTaxonomyTermsCapabilityErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class LoggedInUserHasNoEditingTaxonomyTermsCapabilityErrorPayloadObjectTypeResolverPicker extends AbstractLoggedInUserHasNoEditingTaxonomyTermsCapabilityErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            RootCreateGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver::class,
            RootDeleteGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver::class,
            RootUpdateGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver::class,
            GenericCategoryDeleteMutationErrorPayloadUnionTypeResolver::class,
            GenericCategoryUpdateMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
