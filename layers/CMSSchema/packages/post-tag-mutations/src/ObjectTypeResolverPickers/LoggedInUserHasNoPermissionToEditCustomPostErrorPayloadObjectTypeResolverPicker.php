<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\PostTagMutations\TypeResolvers\UnionType\AbstractPostTagsMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostMutations\ObjectTypeResolverPickers\AbstractLoggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class LoggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeResolverPicker extends AbstractLoggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractPostTagsMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
