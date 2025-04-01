<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\UserMetaMutations\TypeResolvers\UnionType\AbstractUserMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
use PoPCMSSchema\UserMutations\ObjectTypeResolverPickers\AbstractLoggedInUserHasNoPermissionToEditUserErrorPayloadObjectTypeResolverPicker;

class LoggedInUserHasNoPermissionToEditUserErrorPayloadObjectTypeResolverPicker extends AbstractLoggedInUserHasNoPermissionToEditUserErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractUserMetaMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
