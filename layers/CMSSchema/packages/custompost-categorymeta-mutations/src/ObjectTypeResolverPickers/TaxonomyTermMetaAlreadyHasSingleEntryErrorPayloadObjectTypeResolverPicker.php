<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\UnionType\GenericCategoryAddMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\UnionType\RootAddGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\MetaMutations\ObjectTypeResolverPickers\AbstractTaxonomyTermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class TaxonomyTermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolverPicker extends AbstractTaxonomyTermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            RootAddGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver::class,
            GenericCategoryAddMetaMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
