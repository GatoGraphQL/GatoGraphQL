<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\PostCategoryMutations\TypeResolvers\UnionType\AbstractPostCategoriesMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostCategoryMutations\TypeResolvers\UnionType\PostCategoryDeleteMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostCategoryMutations\TypeResolvers\UnionType\PostCategoryUpdateMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostCategoryMutations\TypeResolvers\UnionType\RootCreatePostCategoryTermMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostCategoryMutations\TypeResolvers\UnionType\RootDeletePostCategoryTermMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostCategoryMutations\TypeResolvers\UnionType\RootUpdatePostCategoryTermMutationErrorPayloadUnionTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractGenericErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class GenericErrorPayloadObjectTypeResolverPicker extends AbstractGenericErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractPostCategoriesMutationErrorPayloadUnionTypeResolver::class,
            PostCategoryDeleteMutationErrorPayloadUnionTypeResolver::class,
            PostCategoryUpdateMutationErrorPayloadUnionTypeResolver::class,
            RootCreatePostCategoryTermMutationErrorPayloadUnionTypeResolver::class,
            RootDeletePostCategoryTermMutationErrorPayloadUnionTypeResolver::class,
            RootUpdatePostCategoryTermMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
