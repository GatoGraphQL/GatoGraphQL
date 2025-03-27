<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CategoryMetaMutations\TypeResolvers\UnionType\AbstractCategoryAddMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CategoryMetaMutations\TypeResolvers\UnionType\AbstractRootAddCategoryTermMetaMutationErrorPayloadUnionTypeResolver;
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
            AbstractRootAddCategoryTermMetaMutationErrorPayloadUnionTypeResolver::class,
            AbstractCategoryAddMetaMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
