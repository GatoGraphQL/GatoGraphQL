<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMetaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\PostCategoryMutations\TypeResolvers\UnionType\PostCategoryUpdateMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostCategoryMutations\TypeResolvers\UnionType\RootCreatePostCategoryTermMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostCategoryMutations\TypeResolvers\UnionType\RootUpdatePostCategoryTermMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\MetaMutations\ObjectTypeResolverPickers\AbstractEntityMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class EntityMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolverPicker extends AbstractEntityMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            RootCreatePostCategoryTermMutationErrorPayloadUnionTypeResolver::class,
            RootUpdatePostCategoryTermMutationErrorPayloadUnionTypeResolver::class,
            PostCategoryUpdateMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
