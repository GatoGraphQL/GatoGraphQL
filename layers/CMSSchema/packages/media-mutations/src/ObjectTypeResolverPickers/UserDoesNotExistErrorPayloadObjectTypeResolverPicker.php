<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\MediaMutations\TypeResolvers\UnionType\AbstractCreateMediaItemMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\MediaMutations\TypeResolvers\UnionType\AbstractUpdateMediaItemMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class UserDoesNotExistErrorPayloadObjectTypeResolverPicker extends AbstractUserDoesNotExistErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractCreateMediaItemMutationErrorPayloadUnionTypeResolver::class,
            AbstractUpdateMediaItemMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
