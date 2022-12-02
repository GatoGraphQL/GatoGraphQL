<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CustomPostCategoryMutations\ObjectTypeResolverPickers\AbstractCategoryDoesNotExistErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class CreateUpdatePostCategoryDoesNotExistMutationErrorPayloadObjectTypeResolverPicker extends AbstractCategoryDoesNotExistErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            RootCreatePostMutationErrorPayloadUnionTypeResolver::class,
            RootUpdatePostMutationErrorPayloadUnionTypeResolver::class,
            PostUpdateMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
