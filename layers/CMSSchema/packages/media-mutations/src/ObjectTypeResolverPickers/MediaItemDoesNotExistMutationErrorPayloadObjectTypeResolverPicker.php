<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\MediaMutations\ObjectTypeResolverPickers\AbstractMediaItemDoesNotExistErrorPayloadObjectTypeResolverPicker;
use PoPCMSSchema\MediaMutations\TypeResolvers\UnionType\AbstractCreateMediaItemMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class MediaItemDoesNotExistMutationErrorPayloadObjectTypeResolverPicker extends AbstractMediaItemDoesNotExistErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractCreateMediaItemMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
