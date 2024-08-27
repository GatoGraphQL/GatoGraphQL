<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CustomPostMutations\TypeResolvers\UnionType\GenericCustomPostUpdateMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostMutations\TypeResolvers\UnionType\RootCreateGenericCustomPostMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostMutations\TypeResolvers\UnionType\RootUpdateGenericCustomPostMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostCategoryMutations\TypeResolvers\UnionType\AbstractGenericCategoriesMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\TaxonomyMutations\ObjectTypeResolverPickers\AbstractTaxonomyIsNotValidErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class TaxonomyIsNotValidErrorPayloadObjectTypeResolverPicker extends AbstractTaxonomyIsNotValidErrorPayloadObjectTypeResolverPicker
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
