<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\MediaMutations\ObjectTypeResolverPickers\AbstractLoggedInUserHasNoEditingMediaCapabilityErrorPayloadObjectTypeResolverPicker;
use PoPCMSSchema\MediaMutations\TypeResolvers\UnionType\AbstractCreateMediaItemMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\MediaMutations\TypeResolvers\UnionType\AbstractUpdateMediaItemMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class LoggedInUserHasNoEditingMediaCapabilityErrorPayloadObjectTypeResolverPicker extends AbstractLoggedInUserHasNoEditingMediaCapabilityErrorPayloadObjectTypeResolverPicker
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
