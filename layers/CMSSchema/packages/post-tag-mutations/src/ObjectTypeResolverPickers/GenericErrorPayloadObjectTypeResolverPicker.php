<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\PostTagMutations\TypeResolvers\UnionType\AbstractPostTagsMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostTagMutations\TypeResolvers\UnionType\PostTagDeleteMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostTagMutations\TypeResolvers\UnionType\PostTagUpdateMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostTagMutations\TypeResolvers\UnionType\RootCreatePostTagTermMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostTagMutations\TypeResolvers\UnionType\RootDeletePostTagTermMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostTagMutations\TypeResolvers\UnionType\RootUpdatePostTagTermMutationErrorPayloadUnionTypeResolver;
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
            AbstractPostTagsMutationErrorPayloadUnionTypeResolver::class,
            PostTagDeleteMutationErrorPayloadUnionTypeResolver::class,
            PostTagUpdateMutationErrorPayloadUnionTypeResolver::class,
            RootCreatePostTagTermMutationErrorPayloadUnionTypeResolver::class,
            RootDeletePostTagTermMutationErrorPayloadUnionTypeResolver::class,
            RootUpdatePostTagTermMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
