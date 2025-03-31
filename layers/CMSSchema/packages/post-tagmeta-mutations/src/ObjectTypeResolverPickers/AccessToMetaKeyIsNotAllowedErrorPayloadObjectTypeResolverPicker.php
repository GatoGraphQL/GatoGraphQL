<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMetaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\PostTagMutations\TypeResolvers\UnionType\RootCreatePostTagTermMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostTagMutations\TypeResolvers\UnionType\RootUpdatePostTagTermMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\MetaMutations\ObjectTypeResolverPickers\AbstractAccessToMetaKeyIsNotAllowedErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
use PoPCMSSchema\PostTagMutations\TypeResolvers\UnionType\PostTagUpdateMutationErrorPayloadUnionTypeResolver;

class AccessToMetaKeyIsNotAllowedErrorPayloadObjectTypeResolverPicker extends AbstractAccessToMetaKeyIsNotAllowedErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            RootCreatePostTagTermMutationErrorPayloadUnionTypeResolver::class,
            RootUpdatePostTagTermMutationErrorPayloadUnionTypeResolver::class,
            PostTagUpdateMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
