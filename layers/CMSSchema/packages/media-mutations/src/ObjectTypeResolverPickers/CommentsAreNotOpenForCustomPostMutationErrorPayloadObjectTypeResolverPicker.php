<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\MediaMutations\TypeResolvers\UnionType\RootCreateMediaItemMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class CommentsAreNotOpenForCustomPostMutationErrorPayloadObjectTypeResolverPicker extends AbstractCommentsAreNotOpenForCustomPostMutationErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            RootCreateMediaItemMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
