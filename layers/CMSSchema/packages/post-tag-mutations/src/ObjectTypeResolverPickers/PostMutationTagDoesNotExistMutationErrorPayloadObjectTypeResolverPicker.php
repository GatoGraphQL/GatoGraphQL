<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CustomPostTagMutations\ObjectTypeResolverPickers\AbstractTagDoesNotExistErrorPayloadObjectTypeResolverPicker;
use PoPCMSSchema\PostMutations\TypeResolvers\UnionType\PostUpdateMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostMutations\TypeResolvers\UnionType\RootCreatePostMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostMutations\TypeResolvers\UnionType\RootUpdatePostMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class PostMutationTagDoesNotExistMutationErrorPayloadObjectTypeResolverPicker extends AbstractTagDoesNotExistErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            PostUpdateMutationErrorPayloadUnionTypeResolver::class,
            RootCreatePostMutationErrorPayloadUnionTypeResolver::class,
            RootUpdatePostMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
