<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMetaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\PostMutations\TypeResolvers\UnionType\PostUpdateMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostMutations\TypeResolvers\UnionType\RootCreatePostMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostMutations\TypeResolvers\UnionType\RootUpdatePostMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\MetaMutations\ObjectTypeResolverPickers\AbstractTermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class TermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolverPicker extends AbstractTermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolverPicker
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
