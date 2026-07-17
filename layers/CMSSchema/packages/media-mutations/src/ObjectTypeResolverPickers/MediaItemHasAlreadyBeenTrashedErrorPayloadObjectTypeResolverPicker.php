<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\MediaMutations\TypeResolvers\UnionType\AbstractDeleteMediaItemMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class MediaItemHasAlreadyBeenTrashedErrorPayloadObjectTypeResolverPicker extends AbstractMediaItemHasAlreadyBeenTrashedErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractDeleteMediaItemMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
