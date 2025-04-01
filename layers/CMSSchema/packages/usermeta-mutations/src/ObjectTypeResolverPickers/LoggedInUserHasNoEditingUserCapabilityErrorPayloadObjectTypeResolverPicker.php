<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\UserMetaMutations\TypeResolvers\UnionType\AbstractUserMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\UserMutations\ObjectTypeResolverPickers\AbstractLoggedInUserHasNoEditingUserCapabilityErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class LoggedInUserHasNoEditingUserCapabilityErrorPayloadObjectTypeResolverPicker extends AbstractLoggedInUserHasNoEditingUserCapabilityErrorPayloadObjectTypeResolverPicker
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
